/**
 * @Object data Global
 */
const data = {
  'monthCurrent': new Date().getMonth() + 1,
  'yearCurrent': new Date().getFullYear(),
  'apiReportAll': $('#reportAll').text(),
  'detailEmployee': $('#detailEmployee').text(),
  'exportExcelAll': $('#exportExcelAll').text(),
  'export': $('#exportOneExcel').attr("data-exportOneExcel"),
}
/**
 * @function get number day of month
 * @param year and month
 */
const getNumberOfMonth = (year, month) => {
  return new Date(year, month, 0).getDate();
};
/**
 *@function Check Saturday and Sunday
 @param year month day
 */
function checkSatAndSun(year, month, day) {
  return new Date(`${year}-${month}-${day}`).getDay();
}
/**
 * @function Call API
 * @param year and month
 */
function getListProjectOfEmployeesAPI(year, month) {
  try {
    let yearAndMonth = `${year}-${month}`
    let html = ``, thead = ``, tbody = ``, tfoot = ``;
    let numberOfMonth = getNumberOfMonth(year, month);
    let total = [];
    $.ajax({
      type: "GET",
      url: `${data.apiReportAll}/${yearAndMonth}`,
      dataType: "json",
      success: function (response) {
        // Hiển thị danh sách nhân viên và các dự án đã từng làm theo tháng
        if (response.status === 200) {
          //TODO total X theo tổng các dự án
          let sum = 0;
          let arrTotalProject = [];
          let sumTotal = 0;
          //TODO total X theo tổng các dự án
          const arrayProjectEmployee = response.list;
          arrayProjectEmployee.forEach((key, value) => {
            //TODO: Xuất excel
            let btnExport = `<button id="btnExportOne${key.id}" class="btn btn-success">Export Excel</button>`;
            $(document).on("click", `#btnExportOne${key.id}`, function () {
              var date = yearAndMonth;
              location.href = data.export + '/' + date + '/' + key.id;
            });
            //TODO: Xuất excel
            html += `<table class="table table-primary" border="1px">
            <caption style="caption-side: top;">
            <a href="${data.detailEmployee}/${key.id}">${key.name}</a>
            ${btnExport}
            </caption>`
            thead = `<thead><tr><th class="align-middle">Dự án</th>`
            for (let i = 1; i <= numberOfMonth; i++) {
              let check = checkSatAndSun(year, month, i);
              thead += `<th class="align-middle ${check == 0 ? "bg-primary" : check == 6 ? "bg-info" : false
                }">${i}</th>`;
            }
            thead += `<th class="align-middle">Total</th></tr></thead>`
            tbody = `<tbody>`
            let arrProject = key.name_day_hours
            if (key.name_day_hours != 0) {
              arrProject.forEach((subKey) => {
                if (subKey.id == key.id) {
                  tbody += `<tr><th>${subKey.nameProject}</th>`
                  let days = [];
                  let dayWorks = subKey.days;
                  dayWorks.forEach(function (dayWork) {
                    days.push(dayWork.day_work);
                  });
                  for (let index = 1; index <= numberOfMonth; index++) {
                    if (!total.hasOwnProperty(`${index}-${key.id}`)) {
                      total[`${index}-${key.id}`] = 0;
                    }
                    let keyByDay = days.indexOf(index);
                    let check = checkSatAndSun(year, month, index);
                    if (keyByDay != -1) {
                      tbody += `<td class="align-middle
                      ${check == 0
                          ? "bg-primary"
                          : check == 6
                            ? "bg-info"
                            : false}
                      ">${dayWorks[keyByDay].hours}</td>`
                      //TODO: Total X Project
                      sum += dayWorks[keyByDay].hours;
                      //TODO: Total X Project
                      total[`${index}-${key.id}`] += dayWorks[keyByDay].hours;
                    } else {
                      tbody += `<td class="align-middle  ${check == 0
                        ? "bg-primary"
                        : check == 6
                          ? "bg-info"
                          : false}"></td>`
                    }
                  }
                  //TODO: SUM TOTAL
                  arrTotalProject[`${key.id}${key.name}`] = sum;
                  sum = 0;
                  //TODO: SUM TOTAL
                  tbody += `<td class="align-middle text-center">
                  ${arrTotalProject[`${key.id}${key.name}`]}
                  </td></tr>`
                }
              })
            }
            tbody += `</tbody>`
            tfoot = `<tfoot><tr><th>Total</th>`;
            let sumTotal = 0;
            let arrProjectTotal = [];
            for (let index = 1; index <= numberOfMonth; index++) {
              let check = checkSatAndSun(year, month, index);
              tfoot += `<td class="align-middle
              ${check == 0
                  ? "bg-primary"
                  : check == 6
                    ? "bg-info"
                    : false}
              ">${(total[`${index}-${key.id}`] != 0) ? total[`${index}-${key.id}`] : ""}</td>`
              //TODO: Total Project
              if (total[`${index}-${key.id}`] != 0) {
                sumTotal += total[`${index}-${key.id}`];
                arrProjectTotal[`${key.id}`] = sumTotal;
              }
              //TODO: Total Project
            }
            //TODO: Total Project
            let results = [];
            arrProjectTotal.forEach(key => {
              if (!arrProjectTotal.hasOwnProperty(key)) {
                results = key;
              }
            });
            //TODO: Total Project
            tfoot += ` <td class="align-middle text-center">${results}</td></tr> </tfoot> `
            html += `${thead}${tbody}${tfoot}</table> `
          });
          let btnExportAll = `<button class="btn btn-success">Export Excel</button>`;
          $('#reportAllofEmployee').html(html);
          $('#btnExportAll').html(btnExportAll);
          //TODO: Xuất excel
          let url = data.exportExcelAll;
          $("#btnExportAll button").click(() => {
            location.href = `${url}/${yearAndMonth}`;
          });
          //TODO: Xuất excel
        }
        // Hiển thị danh sách nhân viên và các dự án đã từng làm theo tháng
        else {
          let caption = `<h2>Không có dữ liệu</h2>`
          $('#reportAllofEmployee').html(caption);
          $('#btnExportAll').empty();
        }
      }
    });
  } catch (error) {
    console.error(error);
  }
}
/**
 * @function showProjectOfEmployees
 * @param year month
 */
function showProjectOfEmployee(year, month) {
  getListProjectOfEmployeesAPI(year, month);
}
/**
 * @function showProjectOfEmployeeCurrent
 */
function showProjectOfEmployeeCurrent() {
  let year = data.yearCurrent;
  let month = data.monthCurrent;
  showProjectOfEmployee(year, month);
}
/**
 * change action input month
 */
$("input[name=month]").change(function () {
  var getYear = $(this).val().slice(0, 4);
  var getMonth = $(this).val().slice(5, 8);
  showProjectOfEmployee(getYear, getMonth)
})
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
/**
 * Call all functions
 */
getMonthCurrent()
showProjectOfEmployeeCurrent();
