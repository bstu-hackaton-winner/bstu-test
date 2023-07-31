function remove_user(id){
    swal({
        title: "Вы уверены?",
        text: "Это действие невозможно отменить",
        icon: "warning",
        dangerMode: true,
        buttons: {
            cancel: {
                text: "Отмена",
                visible: true,
                value: false,
                closeModal: true
            },
            confirm: {
                text: "Удалить",
                visible: true,
                value: true,
                closeModal: false
            }
        }
    }).then(result => {
        if(result) {
            $.ajax("/apanel/api.php",{
                url: "/apanel/api.php",
                method: "POST",
                data: {
                    "token": ctoken,
                    "action": "remove_user",
                    "value": id
                },
                success: () => {
                    location.reload();
                },
                fail: () => {
                    swal("Ошибка", "Удалить не получилось, попробуйте позднее", "error").then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}