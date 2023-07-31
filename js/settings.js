function check_passwd(){
    let form = document.forms[1];
    if(form.old_pass.value.length < 6){
        swal("Нужно указать старый пароль");
        return;
    }
    if(form.new_pass.value.length < 6){
        swal("Новый пароль слишком короткий");
        return;
    }
    if(form.new_pass.value != form.new_pass_repeat.value){
        swal("Пароли не совпадают");
        return;
    }
    form.submit();
}