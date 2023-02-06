<?php
namespace App\Http\Controllers;
use App\Models\DayOff;
use App\Models\employeeProject;
use App\Models\User;
use App\Notifications\SlackNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
class SlackController extends Controller
{
    protected $user;
    protected $employeeProject;
    protected $dayOff; //Ngày nghỉ
    public function __construct()
    {
        $this->user = new User();
        $this->employeeProject = new employeeProject();
        $this->dayOff = new DayOff();
    }
    public function slack()
    {
        try {
            $now = Carbon::now(); //Now
            $users = $this->user->where('status', "1")->get();
            //List Users đang làm việc có trạng thái là 1
            $data = [];
            $arrDays = [];
            //Mảng ngày
            $count = 0;
            //Đếm số ngày
            foreach ($users as $key => $value) {
                //Ngày làm việc
                $dayWork = $this->employeeProject
                    ->whereYear('day_work', "=", $now->format("Y"))
                    ->whereMonth('day_work', "=", $now->format("m"))
                    ->whereDay('day_work', "<", $now->format("d"));
                //Ngày làm việc
                //Ngày nghỉ
                $dayOff = $this->dayOff
                    ->whereYear('day_work', "=", $now->format("Y"))
                    ->whereMonth('day_work', "=", $now->format("m"))
                    ->whereDay('day_work', "<", $now->format("d"));
                //Ngày nghỉ
                $employeeProject = $dayWork->where(["user_id" => $value->id]);
                //Nhân viên đã chấm công trong tháng
                if ($employeeProject->exists()) {
                    $checkDayOff = $dayOff->where(["user_id" => $value->id]);
                    //Lấy danh sách ngày nghỉ
                    $arrDayOff = [];
                    foreach ($checkDayOff->get() as $day) {
                        for ($i = 1; $i < $now->format("d"); $i++) {
                            if ((int) Carbon::parse($day->day_work)->format("d") == $i) {
                                array_push($arrDayOff, $i);
                            }
                        }
                    }
                    //Lấy danh sách ngày nghỉ
                    $arrGetDayInMonth = [];
                    //Get Mảng các ngày nhân viên đã làm trong tháng
                    //Lấy danh sách ngày nhân viên đã chấm công trong tháng
                    foreach ($employeeProject->get() as $day) {
                        for ($i = 1; $i < $now->format("d"); $i++) {
                            if ((int) Carbon::parse($day->day_work)->format("d") == $i) {
                                array_push($arrGetDayInMonth, $i);
                            }
                        }
                    }
                    //Thêm vào mảng arrGetDayInMonth
                    foreach ($arrDayOff as $day) {
                        array_push($arrGetDayInMonth, $day);
                    }
                    //Thêm vào mảng arrGetDayInMonth
                    //Lấy danh sách ngày nhân viên đã chấm công trong tháng
                    // So sánh ngày đã chấm công với ngày chưa chấm công
                    for ($i = 1; $i < $now->format("d"); $i++) {
                        if (!in_array($i, $arrGetDayInMonth)) {
                            $format = $i < 10 ? "0$i" : $i;
                            //Format ngày trong tháng nếu ngày đó bé hơn 10 thêm 0 còn lớn hơn thì để nguyên
                            $formatDay = $format . "/" . $now->format("m") . "/" . $now->format("Y");
                            $day = $now->format("m") . "/" . $format . "/" . $now->format("Y");
                            //Kiểm tra thứ bảy và chủ nhật
                            $checkSunday = Carbon::parse($day)->isoFormat("d");
                            $checkSaturday = Carbon::parse($day)->isoFormat("d");
                            //Kiểm tra thứ bảy và chủ nhật
                            if ($checkSunday != "0" and $checkSaturday != "6") {
                                array_push($arrDays, $formatDay);
                                $count++;
                            }
                        }
                    }
                    // So sánh ngày đã chấm công với ngày chưa chấm công
                    $data[] = [
                        'name' => $value->name,
                        'days' => $arrDays,
                        'count' => $count,
                    ];
                    $count=0;
                    $arrDays = [];
                }
                //Nhân viên chưa chấm công trong tháng
                else {
                    $checkDayOff = $dayOff->where(["user_id" => $value->id]);
                    //Lấy danh sách ngày nghỉ
                    $arrDayOff = [];
                    foreach ($checkDayOff->get() as $day) {
                        for ($i = 1; $i < $now->format("d"); $i++) {
                            if ((int) Carbon::parse($day->day_work)->format("d") == $i) {
                                array_push($arrDayOff, $i);
                            }
                        }
                    }
                    for ($i = 1; $i < $now->format("d"); $i++) {
                        if (!in_array($i, $arrDayOff)) {
                            $format = $i < 10 ? "0$i" : $i;
                            //Format ngày trong tháng nếu ngày đó bé hơn 10 thêm 0 còn lớn hơn thì để nguyên
                            $formatDay = $format . "/" . $now->format("m") . "/" . $now->format("Y");
                            $day = $now->format("m") . "/" . $format . "/" . $now->format("Y");
                            //Kiểm tra thứ bảy và chủ nhật
                            $checkSunday = Carbon::parse($day)->isoFormat("d");
                            $checkSaturday = Carbon::parse($day)->isoFormat("d");
                            //Kiểm tra thứ bảy và chủ nhật
                            if ($checkSunday != "0" and $checkSaturday != "6") {
                                array_push($arrDays, $formatDay);
                                $count++;
                            }
                        }
                    }
                    $data[] = [
                        'name' => $value->name,
                        'days' => $arrDays,
                        'count' => $count,
                    ];
                    $count=0;
                    $arrDays = [];
                }
            }
            //Lọc những nhân viên chấm công  tất cả các ngày trong tháng
            $result = [];
            foreach ($data as $key) {
                if ($key['days'] != []) {
                    $result[] = [
                        'name' => $key['name'],
                        'days' => implode(", ", $key['days']),
                        'count' => $key['count'],
                    ];
                }
            }
            //Lọc những nhân viên chấm công  tất cả các ngày trong tháng
            $total = [];
            foreach ($result as $key) {
                $format = $key['name'] . ": " . $key['count'] . " ngày - " . $key['days'];
                array_push($total, $format);
            }
            $this->user->notify(new SlackNotification(implode("\n", $total)));
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
