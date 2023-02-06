<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
class SlackNotification extends Notification
{
    use Queueable;
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }
    /**
     * Get the slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $url="https://report.vaix.group/tablet";
        $img="https://report.vaix.group/assets/website/img/vaixgroup.jfif";
        $message = "Hệ thống chấm công công ty VAIX nhận thấy những thành viên sau đây chưa thực hiện chấm công cho ngày hôm trước. Mong mọi người hoàn thành việc chấm công sớm.";
        return (new SlackMessage)->from("Vaix Daily Report System")->image($img)
            ->content($message . "\n" . $this->user . "\n"."Vui lòng click vào link phía dưới để chấm công")
            ->attachment(function ($attachment) use ($url) {
                $attachment->title($url);
            });
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'name' => $this->user['name']
        ];
    }
}
