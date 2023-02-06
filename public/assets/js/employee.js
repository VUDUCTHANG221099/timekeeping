const date = new Date();
const monthCurrent = date.getMonth() + 1;
const yearCurrent = date.getFullYear();
const idEmployee = $("#idEmployee").attr("data-idEmployee");
const nameEmployee = $("#getName").text();
// const=
/**Count day of month*/
const getNumberOfMonth = (year, month) => {
  return new Date(year, month, 0).getDate();
};
const url = {
  listPlanOfEmployee: $("#listPlanOfEmployee").attr(
    "data-listPlanOfEmployee"
  ),
  listProjectOfEmployeeMonth: $("#listProjectOfEmployeeMonth").html(),
  exportOneReportInAdmin: $("#exportOneReportInAdmin").text(),
  exportPDF: $("#exportPDF").text(),
};
/**Get Month current */
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
function getListProjectOfPlanEmpAPI(year, month) {
  try {
    let yearAndMonth = `${year}-${month}-${idEmployee}`;
    $.ajax({
      type: "GET",
      url: `${url.listPlanOfEmployee}/${yearAndMonth}`,
      dataType: "json",
      success: function (response) {
        if (response.status == 200) {
          let project = response.listProject.map((project) => {
            return `<tr>
                        <td style="width:74%" class="align-middle">${project.name
              }
                        <input type="hidden" value="${project.id
              }" name="project[]" />
                        </td>
                        <td>
                        <div class="d-flex justify-content-center">
                        <input type="text"  class="percent form-control" name="percent[]"
                        value="${project.plan != null ? project.plan : "0"
              }" placeholder="VD 20%" step="any" onkeypress='validate(event)' maxlength="3" >
                           <span class="pt-2">%</span></div></td>
                        </tr>`;
          });
          $("#listProjectEmployee>tbody").html(project);
          let submit = `<input type="submit" class="btn btn-primary "
                    value="Cập nhật">`;
          if (project.length) {
            $("#btn-submit").html(submit);
          } else {
            let message = `<tr>
            <td colspan="2" class="text-center text-danger" style="font-size:26px;border:none  ">
                     Không có dự án</td></tr>`;
            $("#listProjectEmployee>tbody").html(message);
            $("#btn-submit").empty();
          }
        } else {
          // $('#projectAndPercent').attr('class', 'lh-default')
          // $('#cacDuAn').attr('style', 'line-height:130px');
          let message = `<tr>
          <td colspan="2" class="text-center text-danger" style="font-size:26px;border:none  ">
                   ${response.listProject}</td></tr>`;
          $("#listProjectEmployee>tbody").html(message);
          $("#btn-submit").empty();
        }
      },
    });
  } catch (error) {
    console.error(error);
  }
}
// Show Project Current
const showProjectCurrent = () => {
  getListProjectOfPlanEmpAPI(yearCurrent, monthCurrent);
  getListProjectOfEmployee(yearCurrent, monthCurrent);
};
//Show Project Change Month
const showProject = (year, month) => {
  getListProjectOfPlanEmpAPI(year, month);
};
$("input[name=month]").change(function () {
  var getYear = $(this).val().slice(0, 4);
  var getMonth = $(this).val().slice(5, 8);
  showProject(getYear, getMonth);
  getListProjectOfEmployee(getYear, getMonth);
});
/**getAPI list project of employee month */
function getListProjectOfEmployee(year, month) {
  try {
    let data = year + "-" + month + "-" + idEmployee;
    $("#listProjectMonth").attr(
      "data-excel-name",
      `Bảng chấm công tháng ${parseInt(month)}`
    );
    let numberMonth = getNumberOfMonth(year, month);
    $.ajax({
      type: "GET",
      url: `${url.listProjectOfEmployeeMonth}/${data}`,
      dataType: "json",
      success: function (response) {
        let arrProjectMonth = response.listProjectOfEmployeeMonth;
        let html = ``;
        let btnExport = ``;
        if (arrProjectMonth.length > 0) {
          btnExport = ` <button id="export" class="btn btn-primary">Export Excel</button>`;
          html = ` <thead class="thead-dark"><tr>
                  <th colspan="3" class="border text-center d-none"
                   style=" background-color: transparent;">
                    Tên nhân viên: ${nameEmployee}
                  </th>
                  </tr>`;
          html += ` <tr>
                    <th class="text-center" style="width:10%">Ngày</th>
                <th class="text-center">Tên dự án</th><th class="text-center">Số giờ</th>
                </tr></thead>`;
          for (let i = 1; i <= numberMonth; i++) {
            html += `<tbody><tr><td  class="text-center align-middle ">${i}</td>
                    <td><ul style="list-style:none" class="p-0 m-0" >`;
            arrProjectMonth.forEach((key) => {
              let display = `<li class="align-middle p-0 m-0">${key.name}</li>`;
              let result =
                key.day_work.substr(8) == i ? display : "";
              html += result;
            });
            html += `</ul></td><td><ul style="list-style:none">`;
            arrProjectMonth.forEach((key) => {
              let display = `<li class="align-middle">${key.working_hours}h</li>`;
              let result =
                key.day_work.substr(8) == i ? display : "";
              html += result;
            });
            html += `</tr></tbody>`;
          }
          let btnExcel = ` <button id="excel"  class="btn btn-success">Export Excel</button>`;
          let btnPdf = ` <button id="pdf" class="ml-3  btn btn-danger">Export PDF</button>`;
          $("#export-btn").html(`${btnExcel}${btnPdf}`);
          //Xuất excel
          $("#export-btn #excel").click(function () {
            location.href = url.exportOneReportInAdmin + "/" + data;
          });
          //Xuất excel
          //Xuất pdf
          $("#export-btn #pdf").click(function () {
            location.href = url.exportPDF + "/" + data;
          });
          //Xuất pdf
        } else {
          $("#export-btn").empty();
        }
        // $('#btnSummitExport').html(btnExport);
        $("#listProjectMonth").html(html);
      },
    });
  } catch (error) {
    console.error(error);
  }
}
/**
 * @description validate không cho nhập chữ
 */
function validate(evt) {
  var theEvent = evt || window.event;
  // Handle paste
  if (theEvent.type === "paste") {
    key = event.clipboardData.getData("text/plain");
  } else {
    // Handle key press
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}
/**getAPI list project of employee month */
/**Call Function */
showProjectCurrent();
getMonthCurrent();
/**Call Function */
/**Export Excel */
// const exportExcel = () => {
//   var table2excel = new Table2Excel();
//   let exports = $("#export");
//   exports.on("click", () => {
//     let message = confirm("Bạn có muốn xuất excel không?");
//     if (message) {
//       table2excel.export($("#listProjectMonth"));
//     }
//   });
// };
// exportExcel();
