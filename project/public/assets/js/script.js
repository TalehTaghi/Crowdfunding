const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

const OpenModal = (project_id) => {

    $.ajax({
        type: "POST",
        url: "/investment/openModal",
        data: {
            _token: CSRF_TOKEN, project_id: project_id,
        },
        success: function (data) {
            if(data.projectName){
                document.getElementById("projectName").innerText = data.projectName;
                document.getElementById("projectOwner").innerText = data.firstname + " " + data.lastname;
                document.getElementById("raised_fund").innerText = data.totalInvestment;
                document.getElementById("requested_fund").innerText = data.requestedFund;
                document.getElementById("remaining_fund").innerText = data.requestedFund - data.totalInvestment;
                document.getElementById("project_id").value = project_id;
                document.getElementById("investedFund").value = null;
                document.getElementById("investedFund").setAttribute("max", data.requestedFund - data.totalInvestment);
                $('#investmentModal').modal('show');
            }
        },
        error: function () {
            alert('Error... 5011');
        }
    })
};

const IncreaseAmount = (value) => {
    let input = document.getElementById("investedFund");
    if (! input.value) {
        input.value = 0;
    }
    input.value = parseInt(input.value) + value;
}

$('.progress-bar').each(function () {
    let origWidth = $('#widthOfBar').text();

    $(this).animate({
        width: origWidth,
    }, 500, "linear", function () {
        $(this).text(Math.round((parseFloat(origWidth) + Number.EPSILON) * 100)/100 + '%');
    });
})

function showTable(id) {
    $("#hiddenTable"+id).css('display', 'block');
}
