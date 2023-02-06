const date = new Date();
const monthCurrent = date.getMonth() + 1;
const yearCurrent = date.getFullYear();
const _token = $("input[name=_token]").val();
const url = {
  listProjectMonth: $("#listProjectMonth").attr("data-listProject-month"),
  planReality: $("#planReality").attr("data-planReality"),
  totalReality: $("#totalReality").attr("data-totalReality"),
  export: $('#exportOneExcel').attr("data-exportOneExcel"),
  userId: $('#userId').text(),
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
/**Count number of months*/
const getNumberOfMonths = (year, month) => {
  return new Date(year, month, 0).getDate();
};
/**Check Saturday and Sunday */
function checkSatAndSun(year, month, day) {
  return new Date(`${year}-${month}-${day}`).getDay();
}
/**Get Project List By Month */
const getProjectMonthAPI = (year, month) => {
  try {
    let yearAndMonth = `${year}-${month}`;
    let numberOfMonths = getNumberOfMonths(year, month);
    let reality = [];
    let sumHoursOfProject = 0;
    let total = [];
    let arrTotalReality = [];
    let totalXProject = [];
    $.ajax({
      type: "GET",
      url: `${url.listProjectMonth}/${yearAndMonth}`,
      dataType: "json",
      success: function (response) {
        let sumXProject = 0;
        let listProject = response.monthlyProject;
        for (let i = 0; i < listProject.length; i++) {
          for (let j = 0; j < listProject[i].days.length; j++) {
            sumHoursOfProject += listProject[i].days[j].hours;
          }
          reality[listProject[i].idProject] = sumHoursOfProject;
          sumHoursOfProject = 0;
        }
        let tbody;
        if (listProject.length > 0) {
          listProject.map(function (project, i) {
            let dayWorks = project.days;
            let days = [];
            dayWorks.forEach(function (dayWork) {
              days.push(dayWork.day_work);
            });
            tbody += `<tr><td>${project.nameProject}</td>`;
            for (let index = 1; index <= numberOfMonths; index++) {
              if (!total.hasOwnProperty(index)) {
                total[index] = 0;
              }
              let keyByDay = days.indexOf(index);
              let check = checkSatAndSun(year, month, index);
              if (keyByDay != -1) {
                tbody += `<td class="align-middle ${check == 0
                  ? "bg-primary"
                  : check == 6
                    ? "bg-info"
                    : false
                  }">${dayWorks[keyByDay].hours}</td>`;
                total[index] += dayWorks[keyByDay].hours;
              } else {
                tbody += `<td class="align-middle ${check == 0
                  ? "bg-primary"
                  : check == 6
                    ? "bg-info"
                    : false
                  }"></td>`;
              }
              for (let index = 0; index < listProject.length; index++) {
                //TODO: total X to Project
                sumXProject = reality[listProject[index].idProject];
                totalXProject[listProject[index].idProject] = sumXProject;
                //TODO: total X to Project
              }
            }
            tbody += `<td class="align-middle text-center">${totalXProject[project.idProject]}</td></tr>`;
            $("#tableShowMonth> tbody").html(tbody);
          });
          // sumXProject = 0;
          //Export excel
          btnExport = `<button class="btn btn-success" >Export Excel</button>`
          $('#btnExport').html(btnExport);
          $('#btnExport button').click(function () {
            var data = yearAndMonth;
            location.href = url.export + '/' + data + '/' + url.userId;
          });
          //Export excel
        }
        if (listProject == 0) {
          // console.log(1000);
          $("#tableShowMonth> tbody").empty();
        }
        /**Total */
        let sumTotal = 0;
        for (let index = 1; index <= numberOfMonths; index++) {
          if (total[index] != 0) {
            sumTotal += total[index];
          }
        }
        showTotal(year, month, total, listProject, totalXProject);
        let sum = 0;
        for (let index = 0; index < listProject.length; index++) {
          sum =
            (reality[listProject[index].idProject] / sumTotal) *
            100;
          arrTotalReality[listProject[index].idProject] =
            parseInt(sum);
        }
        GetPlanAndRealityAPI(yearAndMonth, arrTotalReality);
      },
    });
  } catch (error) {
    console.error(error);
  }
};
/**Show Month */
const showMonth = (year, month) => {
  $("#tableShowMonth").attr(
    "data-excel-name",
    `Bảng chấm công tháng ${parseInt(month)}-${parseInt(year)}`
  );
  let thead = `<tr><th class="align-middle">Dự án</th>`;
  let numberOfMonths = getNumberOfMonths(year, month);
  for (let index = 1; index <= numberOfMonths; index++) {
    let check = checkSatAndSun(year, month, index);
    thead += `<th class="align-middle ${check == 0 ? "bg-primary" : check == 6 ? "bg-info" : false
      }">${index}</th>`;
  }
  thead += `<th class="align-middle">Total</th></tr>`;
  $("#tableShowMonth> thead").html(thead);
  /**Call API */
  getProjectMonthAPI(year, month); //List Project and hours
  //GetPlanAndRealityAPI(month); //List Project and plan-Reality
};
/**Show Month Current */
const showMonthCurrent = () => {
  showMonth(yearCurrent, monthCurrent);
};
/**Get API plan and Reality */
function GetPlanAndRealityAPI(yearAndMonth, arrTotalReality) {
  try {
    $.ajax({
      type: "GET",
      url: `${url.planReality}/${yearAndMonth}`,
      dataType: "json",
      success: function (response) {
        let tbody;
        let planReality = response.planReality;
        if (planReality != 0) {
          planReality.map((item) => {
            //$("#tablePlanReality tbody").empty();
            tbody += `<tr><td class="align-middle">${item.nameProject
              }</td>
                    <td class="align-middle">${item.plan ?? 0}%</td>
                    <td class="align-middle" id='hideReality'>${arrTotalReality[item.project_id] ?? 0
              }%</td>
                    </tr>`;
            $("#tablePlanReality tbody").html(tbody);
          });
        }
        else {
          // console.log(1000);
          $('#btnExport').empty();
          $("#tableShowMonth> tbody").empty();
        }
      },
    });
  } catch (error) {
    console.error(error);
  }
}
/**Total  */
function showTotal(year, month, total, listProject, sumTotal) {
  let numberOfMonths = getNumberOfMonths(year, month);
  //Sum X total X =>Tính tổng theo chiều dọc của cột total
  var sum = sumTotal.reduce(function (a, b) {
    return a + b;
  }, 0);
  //Sum X total X =>Tính tổng theo chiều dọc của cột total
  if (listProject.length > 0) {
    let tfoot = `<tr><td>Total</td>`;
    for (let index = 1; index <= numberOfMonths; index++) {
      let check = checkSatAndSun(year, month, index);
      tfoot += `<td class="align-middle ${check == 0 ? "bg-primary" : check == 6 ? "bg-info" : false
        }">${total[index] != 0 ? total[index] : ""}</td>`;
    }
    tfoot += `<td class="align-middle text-center">${sum}</td></tr>`
    $("#tableShowMonth> tfoot").html(tfoot);
  } else {
    $("#tableShowMonth> tfoot").empty();
  }
}
/**Change Month */
$("input[name=month]").change(function () {
  var getYear = $(this).val().slice(0, 4);
  var getMonth = $(this).val().slice(5, 8);
  $("#tablePlanReality tbody").html("");
  showMonth(getYear, getMonth);
});
/**Call functions*/
showMonthCurrent();
getMonthCurrent();
/**Call functions*/
/***************************************** */
