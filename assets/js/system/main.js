function loading(status = true){
    if(!status){ 
        $('#loading').css('display', 'none');
    }else{
        $('#loading').css('display', 'block');
    }
}