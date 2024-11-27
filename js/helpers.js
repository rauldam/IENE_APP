    /**********************
    **      HELPERS      **
    **********************/
    
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;
        
        var fecha = [day, month, year].join('/');
        var hora = [d.getHours(), d.getMinutes(), d.getSeconds()].join(':');
        var fechaTotal = fecha+" "+hora;
        return fechaTotal
    }
    function can_read(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var read;
        if(myRoles[0]['leer'] == 's'){
            read = true;
        }else{
            read = false;
        }
        return read;
    }
    function can_write(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var write;
        if(myRoles[0]['escribir'] == 's'){
            write = true;
        }else{
            wrrite = false;
        }
        return write;
    }
    function can_edit(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var edit;
        if(myRoles[0]['editar'] == 's'){
            edit = true;
        }else{
            edit = false;
        }
        return edit;
    }
    function can_read_obs(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var obs;
        if(myRoles[0]['leer_obs'] == 's'){
            obs = true;
        }else{
            obs = false;
        }
        return obs;
    }
    function can_obs(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var obs;
        if(myRoles[0]['observaciones'] == 's'){
            obs = true;
        }else{
            obs = false;
        }
        return obs;
    }
    function can_state(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var state;
        if(myRoles[0]['estados'] == 's'){
            state = true;
        }else{
            state = false;
        }
        return state;
    }
    function can_create(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var create;
        if(myRoles[0]['crear'] == 's'){
            create = true;
        }else{
            create = false;
        }
        return create;
    }
    function can_delete(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var eliminar;
        if(myRoles[0]['eliminar'] == 's'){
            eliminar = true;
        }else{
            eliminar = false;
        }
        return eliminar;
    }
    function can_report(myRoles){
        myRoles = JSON.parse(myRoles);
        log(myRoles,'debug');
        var report;
        if(myRoles[0]['informes'] == 's'){
            report = true;
        }else{
            report = false;
        }
        return report;
    }
      
      