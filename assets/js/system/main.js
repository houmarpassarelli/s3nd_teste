function loading(status = true, full = false){
    if(!status){ 
        $('#loading').css('display', 'none');
    }else{

        var css = {
            'display' : 'block'
        }

        if(full){
            css = {
                'display' : 'block',
                'top' : 0
            }   
        }

        $('#loading').css(css);
    }
}