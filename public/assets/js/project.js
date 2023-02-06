const date = new Date();
const monthCurrent = date.getMonth() + 1;
const yearCurrent = date.getFullYear();
const idProject = $("#idProject").text();
const url = {
    apiEmployeeAndProjectCount: $("#apiEmployeeAndProjectCount").text(),
    apiRemoveEmployee: $("#apiRemoveEmployee").text(),
};
const getMonthCurrent = () => {
    flatpickr("#month_flatpickr", {
        // locale: "ja",
        maxDate: "today",
        plugins: [
            new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "Y/m", //defaults to "F Y"
                altFormat: "F Y", //defaults to "F Y"
                theme: "light", // defaults to "light"
            }),
        ],
    });
};
/*Call API* */
function getCountAndEmployeeAPI(year, month) {
    try {
        const data = year + '-' + month + '-' + idProject;
        $.ajax({
            type: "GET",
            url: `${url.apiEmployeeAndProjectCount}/${data}`,
            dataType: "json",
            success: function (response) {
                const arrEmployee = response.listEmployee;
                let countEmployee = response.countEmployee > 0 ?
                    response.countEmployee : "<span class='text-danger'>Hiện chưa có người tham gia vào dự án!</span>";
                $('#countEmployeeJoinProject').html(countEmployee);
                let html = '';
                if (response.countEmployee > 0) {
                    html = arrEmployee.map((key) => {
                        return `<tr>
                            <td>
                        ${key.name}</td>
                        <td>
                        <button type="submit"
                         onclick="removeEmployee(${year},${month},${key.id})"class="btn btn-danger btn-sub">
                        <i class="fa fa-trash" ></i>
                    </button>
                        </td>
                        </tr>`;
                    })
                } if (response.countEmployee == 0) {
                    $('#dsntgda').attr('style', 'line-height:46px')
                    html = `<tr class="bh-base">
                    <td style=" border:none"  class='text-danger'>
                    Danh sách nhân viên tham gia đang trống!</td></tr>`
                }
                $('#listEmployee').html(html);
            }
        });
    } catch (error) {
        console.error(error);
    }
}
/*Call API* */
/*Call Employee and count current */
const getCountAndEmployeeCurrent = () => {
    getCountAndEmployeeAPI(yearCurrent, monthCurrent);
}
/*Call Employee and count current */
$("input[name=month]").change(function () {
    var getYear = $(this).val().slice(0, 4);
    var getMonth = $(this).val().slice(5, 8);
    getCountAndEmployeeAPI(getYear, getMonth);
})
function removeEmployee(year, month, id) {
    try {
        const data = year + '-' + month + '-' + idProject + '-' + id;
        $.ajax({
            type: "GET",
            url: `${url.apiRemoveEmployee}/${data}`,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    location.reload()
                }
            }
        });
    } catch (error) {
        console.error(error);
    }
}
/**Call function */
getMonthCurrent();
getCountAndEmployeeCurrent();
/**Call function */
