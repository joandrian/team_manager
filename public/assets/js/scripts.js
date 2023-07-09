//For Ajax Retrieving Players
$('#marketplace_seller').on('change', (event) => {
    const teamId = event.target.value;
    $.ajax({
        url: '/marketplace',
        data: {
            teamId: teamId,
        },
        success: function (data) {
            if (data.length > 0) {
                $("#playerlist").show();
                $("#anyplayer").hide();
                $("#playerlist .sel_player").empty();
                $("#playerlist .sel_player").attr("size", data.length);
                $.each(data, function (index, value) {
                    $("#playerlist .sel_player").append(
                        '<option value="' + value.id + '">' + value.name + ' ' + value.surname + '</option>'
                    );
                });
            }
            else {
                $("#playerlist").hide();
                $("#anyplayer").show();
            }
        },
    });
});


//For submit 
$("form[name='marketplace']").submit(
    function (e) {
        // Stop the form submitting
        e.preventDefault();
        let sellerTeam = $("#marketplace_seller option:selected").val();
        let buyerTeam = $("#marketplace_buyer option:selected").val();
        if (sellerTeam == buyerTeam) {
            $("#teamSelectError").html("Please select different Buyer team").addClass("alert alert-danger");
        } else {
            e.currentTarget.submit();
        }
    }
);