//Date current
const date = new Date();
const monthCurrent = date.getMonth() + 1;
const yearCurrent = date.getFullYear();
let totalY = [];
let arrTotalX = [];
let arrSumProjects = [];
//Date current
const API = {
  ScheduleAndReality: $.trim($('#APIScheduleAndReality').text()),
  ProjectAndEmployeeToMonth: $.trim($("#APIProjectAndEmployeeToMonth").text()),
}
const URL = {
  export: $.trim($("#exportAdmin").text()),
}
//TODO: Check Saturday and Sunday
function checkSatAndSun(year, month, day) {
  return new Date(`${year}-${month}-${day}`).getDay();
}
//TODO: Check Saturday and Sunday
//count the number of days in the month
//Đếm số ngày của một tháng VD: tháng 1 có 31 ngày
const getNumberInMonth = (year, month) => {
  return new Date(year, month, 0).getDate();
};
//count the number of days in the month
//Get month current
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
//TODO Call API apiScheduleAndReality
function ScheduleAndRealityAPI(year, month, total, sumProject) {
  //TODO tính total Y
  let sumTotal = total.reduce((partialSum, a) => partialSum + a, 0);
  //TODO tính total Y
  //TODO tính % từng dự án
  let arr = sumProject.map((i) => {
    return parseInt((i / sumTotal) * 100);
  })
  //TODO tính % từng dự án
  let yearAndMonth = `${year}-${month}`;
  let tbody = ``;
  let i = 0;
  $.ajax({
    type: "GET",
    url: API.ScheduleAndReality + '/' + yearAndMonth,
    data: "json",
    success: function (response) {
      if (response.status === 200) {
        let arrPlan = response.plans;
        tbody += `<tr>`;
        arrPlan.forEach((key) => {
          tbody += `<td>${key['name']}</td><td>${key['plan']}%</td><td>${arr[i]}%</td></tr>`
          i++;
        });
        $("#tablePlanReality >tbody").html(tbody);
      } else {
        $("#tablePlanReality >tbody").empty();
      }
    }
  });
}
//TODO Call API apiScheduleAndReality
function ProjectAndEmployeeToMonthAPI(year, month) {
  let countOfMonth = getNumberInMonth(year, month);
  let yearAndMonth = `${year}-${month}`;
  //TODO lấy số ngày trong tháng
  getNumberOfMonth(year, month);
  //TODO lấy số ngày trong tháng
  $.ajax({
    type: "GET",
    url: API.ProjectAndEmployeeToMonth + '/' + yearAndMonth,
    data: "json",
    success: function (res) {
      let data = res.data;
      render(year, month, data, countOfMonth);
    }
  });
}
//TODO: render tên dự án và tên nhân viên ...
function render(year, month, data, countOfMonth) {
  let arrTotalHoursProject = []
  let tbody = ``;
  if (data.length > 0) {
    data.forEach(key => {
      let arrProject = key[0].filter(function (val, index, arr) {
        return index < 1;
      })
      arrTotalHoursProject = arrSumProjects.filter(function (val, index, arr) {
        return val > 0;
      })
      arrProject.forEach(sub => {
        tbody += `<tr>`
        tbody += `<td class="align-middle text-center"
        rowspan="${key.count > 0 ? key.count : 1}">${arrProject[0].nameProject}      </td>`
      })
      let totalHours = 0;
      key[0].forEach(name => {
        tbody += `<td class="align-middle">${name.name}</td>`
        //TODO loop day
        let arrDay = name.dayWorks;
        let days = [];
        let totalX = 0;
        arrDay.forEach(day => days.push(day.day));
        for (let i = 1; i <= countOfMonth; i++) {
          //Check Saturday and Sunday
          let check = checkSatAndSun(year, month, i);
          //Check Saturday and Sunday
          //TODO total Y
          if (!totalY.hasOwnProperty(i)) {
            totalY[i] = 0;
          }
          //TODO total Y
          let keyByDay = days.indexOf(i);
          if (keyByDay != -1) {
            tbody += `<td class="align-middle text-center
            ${check == 0
                ? "bg-primary"
                : check == 6
                  ? "bg-info"
                  : false
              }"
            ">${arrDay[keyByDay].hours}</td>`
            totalY[i] += arrDay[keyByDay].hours;
            totalX += arrDay[keyByDay].hours;
          } else {
            tbody += `<td class="align-middle
            ${check == 0
                ? "bg-primary"
                : check == 6
                  ? "bg-info"
                  : false
              }"></td>`
          }
        }
        //TODO loop day
        totalHours += totalX;
        tbody += `<td class="align-middle text-center">${totalX}</td>
      </tr>`
      })
      arrTotalX.push(totalHours);
      arrSumProjects.push(totalHours);
    })
    ScheduleAndRealityAPI(year, month, totalY, arrTotalX);
    total(year, month, countOfMonth, totalY);
    arrTotalX = [];
    totalY = [];
    $('#tableShowMonth>tbody').html(tbody);
    //TODO Export Excel
    // btnExport = `<button class="btn btn-success" >Export Excel</button>`
    // $('#btnExport').html(btnExport);
    // $('#btnExport button').click(function () {
    //   var data = `${year}-${month}`;
    //   location.href = URL.export + '/' + data;
    // });
    //TODO Export Excel
  } else {
    $('#tableShowMonth>tbody').empty();
    $("#tablePlanReality >tbody").empty();
    $('#tableShowMonth>tfoot').empty();
    $('#btnExport').empty();
  }
}
//TODO: render tên dự án và tên nhân viên ...
//TODO: total countOfMonth: số ngày trong tháng
function total(year, month, countOfMonth, totalY) {
  tfoot = `<tr><th class="text-center">Total</th><td></td>`;
  for (let i = 1; i <= countOfMonth; i++) {
    //TODO: check saturday and sunday
    let check = checkSatAndSun(year, month, i);
    //TODO: check saturday and sunday
    tfoot += `<td class="text-center align-middle ${check == 0
      ? "bg-primary"
      : check == 6
        ? "bg-info"
        : false
      }">${totalY[i] != 0 ? totalY[i] : ""}</td>`;
  }
  //TODO total Y
  let sum = totalY.reduce((partialSum, a) => partialSum + a, 0);
  //TODO total Y
  tfoot += `<td colspan="2" class="text-center">${sum}</td>`
  $('#tableShowMonth>tfoot').html(tfoot);
}
//TODO: total
//TODO show plan Current
function showProjectCurrentAndPlan() {
  ProjectAndEmployeeToMonthAPI(yearCurrent, monthCurrent);
}
//TODO show plan Current
//TODO Change calendar
$("input[name=month]").change(function () {
  var getYear = $(this).val().slice(0, 4);
  var getMonth = $(this).val().slice(5, 8);
  ProjectAndEmployeeToMonthAPI(getYear, getMonth);
});
//TODO Change calendar
//TODO Get month: lấy số ngày trong tháng
function getNumberOfMonth(year, month) {
  let countOfMonth = getNumberInMonth(year, month);
  let thead = `<tr style="text-align:center"><th>Dự án</th><th>Thành viên</th>`
  for (let index = 1; index <= countOfMonth; index++) {
    //TODO: check saturday and sunday
    let check = checkSatAndSun(year, month, index);
    //TODO: check saturday and sunday
    thead += `<th  class="align-middle ${check == 0
      ? "bg-primary"
      : check == 6
        ? "bg-info"
        : false
      }">${index}</th>`;
  }
  thead += `<th colspan="2"  class="align-middle" >Total</th></tr>`;
  $("#tableShowMonth>thead").html(thead);
}
//TODO Get month: lấy số ngày trong tháng
//Call function
getMonthCurrent();
showProjectCurrentAndPlan();
