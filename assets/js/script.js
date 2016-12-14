var msgdigitada = [];
var hrmsgdigitada = [];
var idmsgrecebida = [];
var idmsgenviada = [];
var focus = "";
var nome = "";

function getChamado() {
    $.ajax({
        url:'/ajax/getchamado',
        dataType:'json',
        success:function(json) {
            $('.chamado').remove();
            if(json.chamados.length > 0) {
                for(var i in json.chamados) {
                    if(json.chamados[i].status === '1') {
                            $('#areadechamados').append("<tr class='chamado' data-id='"+json.chamados[i].id+"'><td>"+json.chamados[i].data_inicio+"</td><td>"+json.chamados[i].nome+"</td><td><a href='' onclick='abrirChamado(this)'>Em Atendimento</a></tr>");
                    } else {
                            $('#areadechamados').append("<tr class='chamado' data-id='"+json.chamados[i].id+"'><td>"+json.chamados[i].data_inicio+"</td><td>"+json.chamados[i].nome+"</td><td><button onclick='abrirChamado(this)'>Abrir Chamado</button></td></tr>");
                    }
                }
            } 
                setTimeout(getChamado, 2000);
        },
        error:function(){
                setTimeout(getChamado, 2000);
        }
    });
}

function abrirChat() {
    window.open("/chat", "chatWindow", "width=400,height=400,left=545,top=265");
}

function abrirChamado(obj) {
    var id = $(obj).closest('.chamado').attr('data-id');
    window.open("/chat?id="+id, "chatWindow", "width=400,height=400,left=950,top=265");
}

function iniciarSuporte() {
    setTimeout(getChamado, 2000);
}

function startChat(){
    setTimeout(getMessage,2000);
    setTimeout(sendMessage,2000);
    setTimeout(receivedMessage,2000);
    setTimeout(readMessage, 2000);
    setTimeout(getReadMessage, 2000); 
    setTimeout(getNome, 2000); 
}

function getNome(){
    if (nome === "") {
        $.ajax({
            url:"/ajax/getNome",
            dataType:"json",
            success:function(json){
                nome = json.nome['nome'].toUpperCase();
                $('.nome').html(nome);
            },
            error:function(){
                alert("Error json getNome");
            }
        });
    } 
    setTimeout(getNome, 2000);
}

function cutMessage(msg){
    var size = 30;
    if(msg.length > size){
        var me = msg;
        msg = "";
        var m = "";
        while(me.length > size){
            m = me.substring(0,size);
            msg += m.substring(0, m.lastIndexOf(" "))+"<br/>"+m.substring(m.lastIndexOf(" "), m.length);
            me = me.substring(size, me.length);
        }
        msg += me;
    }  
    return msg;
  }

function hour(){
    var data = new Date();
    var hr = (data.getHours() < 10)?"0"+data.getHours()+":":data.getHours()+":";
    hr += (data.getMinutes() < 10)?"0"+data.getMinutes():data.getMinutes();
    return hr;
}
    
function keyUpChat(obj, event) {
    if(event.keyCode === 13){    
        var msg = obj.value.trim();
        var hr = hour();
        if(obj.value !== ""){
            msgdigitada.push(msg);
            hrmsgdigitada.push(hr);
            $('.chatarea').append("<div class='msgenviada'>"+cutMessage(msg)+"  "+hr+"<img id='A"+msgdigitada.length+"' src='assets/images/relogio.png'/>"+"</div>"+"<div style='clear:both;'></div>");
          obj.value = "";
          $('.chatarea').scrollTop($('.chatarea')[0].scrollHeight);
        }
    }
}

function sendMessage(){
    for (var i in msgdigitada) {
        $.ajax({
            url:"/ajax/sendMessage",
            type:"POST",
            data:{msg:msgdigitada[i],hr:hrmsgdigitada[i]},
            dataType:"json",
            success:function(json){
                if (json.id['id'] > 0) {
                    $('#A'+parseInt(i+1)).attr('id',json.id['id']).attr('src','assets/images/seta.png');
                    idmsgenviada.push(json.id['id']);
                    msgdigitada.shift();
                    hrmsgdigitada.shift();
                }    
            },
            error:function(){
                alert("Error sendMessage");
            }
        });  
    }
    setTimeout(sendMessage,2000);
}

function getMessage(){
    $.ajax({
        url:"/ajax/getMessage",
        dataType:"json",
        success:function(json){
            if (json.mensagens[0].id > 0) {
                for(var i in json.mensagens){
                    json.mensagens[i].mensagem = cutMessage(json.mensagens[i].mensagem);
                    $('.chatarea').append("<div class='msgrecebida'>"+json.mensagens[i].mensagem+"  "+json.mensagens[i].data_enviado+"</div>"+"<div style='clear:both;'></div>");
                    idmsgrecebida.push(json.mensagens[i].id);
                    $('.chatarea').scrollTop($('.chatarea')[0].scrollHeight);
                }
            }     
        },
        error:function(){
            alert("Error getMessage");
        }
    });
    setTimeout(getMessage,2000);
}

function receivedMessage(){
    $.ajax({
        url:"/ajax/receivedMessage",
        dataType:"json",
        success:function(json){
           for(var i in json.id){
                $('#'+json.id[i].id).attr('src', 'assets/images/setas.png');
            }
        },
        error:function(){
            alert("Error json receivedMessage");
        }
    });
    setTimeout(receivedMessage,2000);
}

function setFocus(foco){
    focus = foco;
}

function readMessage(){
    if (focus === "ativo") {
        for(var i in idmsgrecebida){
           $.ajax({
                url:"/ajax/readMessage",
                type:"POST",
                data:{id:idmsgrecebida[i]},
                success:function(json){
                    if (json.id['id'] > 0) {
                        idmsgrecebida.shift();
                    }
                },
                error:function(){
                  alert("Error json readMessage");  
                }
            }); 
        }      
    } 
    setTimeout(readMessage, 2000);
}

function getReadMessage(){
    if (focus === "ativo") {
        if (idmsgenviada.length > 0) {
            for(var i in idmsgenviada){
                $.ajax({
                    url:"/ajax/getReadMessage",
                    dataType:"json",
                    type:"POST",
                    data:{id:idmsgenviada[i]},
                    success:function(json){
                        if (json.id['id'] > 0) {
                            $('#'+json.id['id']).attr('src', 'assets/images/setasazuis.png');
                            idmsgenviada.shift();          
                        }
                    },
                    error:function(){
                        alert("Error json getReadMessage");
                    }
                });
            }
        }
    }
    setTimeout(getReadMessage, 2000);
}