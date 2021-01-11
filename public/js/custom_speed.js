"use strict";


/*=========================================
            AJAX Request
=========================================*/

/**
 * 
 * @param {*} method the HTTP request method (POST, GET...)
 * @param {*} url the url request
 * @param {*} obj any JSON object that you want
 */
function ajaxMethod(method, url, obj) {
    return new Promise((resolve, reject) => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: method,
            url: url,
            dataType: 'JSON',
            data: obj,
            success: data => {
                resolve(data);
            }, error: err => {
                reject(err);
            }
        });
    });
}




/*=========================================
            SweetAlert2 Custom
=========================================*/

/**
 * Warning Message
 * 
 * @param {*} text message that will be displayed for more details of the consequence of the action
 */
function swal2_warning(text, buttonText = 'Sim, excluir!', colorConfirm = "#DD6B55" ) {
    return new Promise((resolve, reject) => {
        swal({   
            title: "Você tem certeza?",   
            text: text,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: colorConfirm,   
            confirmButtonText: buttonText,   
            cancelButtonText: "Cancelar",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {     
                resolve(true);
            } else {     
                reject(false);
            }  
        });
    });
}

/**
 * Input Message
 * 
 * @param {*} text message that will be displayed for more details of the consequence of the action
 */
function swal2_input(text, placeholder, msg_erro) {
    return new Promise((resolve, reject) => {
        swal({
            title: text,
            text: '',
            type: "input",
            name: "nameDashboard",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: placeholder
        },
        function(inputValue){
            if (inputValue === false) return false;
  
            if (inputValue === "") {
                swal.showInputError(msg_erro);
                return false;
            }
            //swal2_success("Sucesso!","Dashboard: " + inputValue +" cadastrado com sucesso.")
            //swal("Sucesso!", "Dashboard: " + inputValue +" cadastrado com sucesso.", "success");
            resolve(inputValue);
        });
    });    
}

/**
 * Success Message and Page Reload
 * 
 * @param {*} tile alert title message
 * @param {*} text message that will be displayed for more details of the consequence of the action
 */
function swal2_success(title, text) {
    swal({   
        title: title,   
        text: text,
        type: "success"
    }, function(){   
        location.reload();  
    });
}

/**
 * Error Message and Page Reload
 */
function swal2_alert_error_support(text) {
    swal({
        title: "Ops!",   
        text: text + " Por favor, contate o suporte técnico!",
        type: "warning"
    }, function(){   
        location.reload();  
    });
}

/**
 * Error Message and not Page Reload
 */
function swal2_alert_error_not_reload(text) {
    swal({
        title: "Ops!",   
        text: text ,
        type: "warning"
    }, function(){   
        
    });
}

/**
 * Success Message and not Page Reload
 */

function swal2_success_not_reload(title, text) {
    swal({   
        title: title,   
        text: text,
        type: "success"
    }, function(){   
    });
}



/*=========================================
            toastr.js
=========================================*/

/**
 * Simple method to generalize the display of a toastr
 * 
 * @param {*} h toastr title message
 * @param {*} t toastr description/body content
 * @param {*} i define the toastr type, e.g.: info, warning, success and error
 */
function showToast(h, t, i) {
    $.toast({
        heading: h,
        text: t,
        position: 'top-right',
        loaderBg:'#ff6849',
        icon: i,
        hideAfter: 3500, 
        stack: 6
    });
}

/**
* METODO QUE MONTA CAMPOS DOS PROCESSOS DINAMICAMENTES
*
* @param {Object} $_indices    - Lista de indices dos processos
* @param {Object} $_component  - Componente do DOM aonde será feito o append
* @param {Object} $_infoInputs - Informações de mascaras e valores para os "inputs" (está definido no arquivo de Constantes.php - $OPTIONS_TYPE_INDICES_GED) 
* @param {Object} $_config     - Lista de configs 
    {
        required - define se os campos serão obrigatórios - valor padrao = false
        disabled - define se os campos serão desabilitados caso já possua um valor definido para o campo - valor padrao = false 
        col_md_size - define o tamanho da col-md das divs dos componentes - valor padrao = 4
        onlyComponents - define se serão retornados apenas os campos sem suas divs - valor padrao = false
        valoresPreDefinidos - Objeto com valores padrões para os componentes - valor padrao = {}
    } 
*/
function createFiltersComponentsGED($_indices, $_component, $_infoInputs, $_config) {
    
    let components = "";
    let idTipoIndice = "";
    let infoInput = "";

    // CONFIGS
    let required = $_config.required ?? false;
    let disabled = $_config.disabled ?? false;
    let col_md_size = $_config.col_md_size ?? 4;
    let onlyComponents = $_config.onlyComponents ?? false;
    let valoresPreDefinidos = $_config.valoresPreDefinidos ?? {};


    $.each($_indices, function (idx, el) {

        if (!onlyComponents) {
            components += '<div class="col-md-' + col_md_size + ' mt-3">';
            components += "<label>" + el.descricao + "</label>";                            
        }
        
        idTipoIndice = el.idTipoIndice;
        
        infoInput = $_infoInputs[idTipoIndice];
        
        let valor = valoresPreDefinidos[el.identificador] ?? ""; 
        let readonly = disabled && valor ? "disabled" : ""; 

        if (required) {
            required = el.preenchimentoObrigatorio ? "required" : "";
        }

        switch (idTipoIndice) {

            case "1":
            case 1:
                //tipo boolean
                components += "<select name='" + el.identificador + "' id='" + el.identificador + "' data-indice='" + idTipoIndice + "' data-identificador='" + el.identificador + "' class='form-control submitComponent' " + required + " " + readonly + ">";                            
                
                $.each(infoInput.selectOptions, function(key, val){
                    components += "<option value='" + key + "' " + (key == valor ? 'selected' : '') + ">" + val + "</option>"
                });

                components += "</select>";

                break;

            case "12":
            case 12:
                components += "<select name='" + el.identificador + "' id='" + el.identificador + "' data-indice='" + idTipoIndice + "' data-identificador='" + el.identificador + "' class='form-control submitComponent' " + required + " " + readonly + ">";
                components += "<option value=''>Selecione</option>"
                $.each(el.listaMultivalorado, function(key, val){
                    components += "<option value='" + val.descricao + "' " + (val.descricao == valor ? 'selected' : '') + "  >" + val.descricao + "</option>"
                });
                components += "</select>";

                break;

            case "17":
            case 17:

                infoInput = arrayTipoIndiceGED[el.idTipoIndice];

                components += "<input name='" + el.identificador + "' id='" + el.identificador + "' type='" + infoInput.htmlType + "' data-indice='" + idTipoIndice + "' data-identificador='" + el.identificador + "' class='form-control submitComponent " + infoInput.cssClass + " ' " + required + " value='" + valor + "' " + readonly + " />";

                break;
        
            default:
                components += "<input name='" + el.identificador + "' id='" + el.identificador + "' type='" + infoInput.htmlType + "' data-indice='" + idTipoIndice + "' data-identificador='" + el.identificador + "' class='form-control submitComponent " + infoInput.cssClass + " ' " + required + " value='" + valor + "' " + readonly + " />";
                
                break;               
        }
        if (!onlyComponents) {
            components += "</div>";
        }
    });

    $($_component).append(components);
    makeMasks();
}

function makeMasks()
{
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('00000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
        'Z': {
            pattern: /[0-9]/, optional: true
        }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
    $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
    $('.fallback').mask("00r00r0000", {
        translation: {
            'r': {
            pattern: /[\/]/,
            fallback: '/'
            },
            placeholder: "__/__/____"
        }
        });
    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
}