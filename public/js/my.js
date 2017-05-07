Vue.component('alert-box',{
    props:['message'],
    template:'' +
    '<div class="alert alert-success alert-dismissible animated fadeOut">\
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
        <h4><i class="icon fa fa-check"></i> Alert!</h4>\
            {{ message }}\
    </div>'
});

Vue.component('alert-modal',{
    props:['title','message','ids'],
    template:'' +
    ' <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel">\
        <div class="modal-dialog" role="document">\
            <div class="modal-content">\
                <div class="modal-header">\
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
                    <h4 class="modal-title" id="alertModalLabel">{{ title }}</h4>\
                </div>\
                <div class="modal-body">\
                {{ message }}\
                </div>\
                <div class="modal-footer">\
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
                    <button type="button" class="btn btn-primary">Save changes</button>\
                </div>\
            </div>\
        </div>\
    </div>'
    });

new Vue({
    el:'#alert-boxes'
});

function alertModal(title,body,color){
    $('#alertModal .modal-title').html(title);
    $('#alertModal .modal-body').html(body);
    $('#alertModal').attr('class',"modal fade"+color)
    $('#alertModal .modal-dialog').attr('class',color+ ' modal-dialog  bounceIn animated');
    $('#alertModal').modal();
}


