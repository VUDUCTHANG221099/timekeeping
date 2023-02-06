// import Config from "./config.js";
const hideErrors = () => {
  setTimeout(() => {
    $(".danger_hide").hide();
  }, 3000);
};
const hideSuccess = () => {
  setTimeout(() => {
    $(".flashMessage").hide();
  }, 3000);
};
const apiEmployees = () => {
  try {
    let url = $(`#apiEmp`).attr("data-apiListEmployee");
    if (url != null) {
      let idProject = $("input[name='idProject']").val();
      let subUrl = `${url}/${idProject}`;
      url = url.replace(":id", idProject);
      $.ajax({
        type: "GET",
        url: subUrl,
        dataType: "json",
        success: function (response) {
          let listEmployee = response.listEmployee;
          var settings1 = {
            dataArray: listEmployee,
            itemName: "name",
            callable: function (items) {
              // console.dir(items);
            },
          };
          $("#transfer1").transfer(settings1);
          let html = `<button type="submit" class="btn btn-primary">Thêm</button>`;
          $("#addEmployee").html(html);
        },
      });
    }
  } catch (error) {
    console.log(error);
  }
};
hideErrors();
hideSuccess();
apiEmployees();
