-(function($){
    "use strict";
    var KTDatatableRemoteAjaxDemo = {
        init: function (admin) {
            var personnes;
            var datatable;
            var url = admin ? '/admin/personnes/data' : '/personnes/data';
            var options = {
                data: {
                    type: 'remote',
                    source: {
                        read: {
                            url: url,
                            map: function(raw) {
                                // sample data
                                var dataSet = raw.personnes;
                                personnes = dataSet;
                                if (typeof raw.data !== 'undefined') {
                                    dataSet = raw.data;
                                }
                                return dataSet;
                            }
                        }
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true,
                },
                // layout definition
                layout: {
                    scroll: false,
                    footer: false,
                    spinner: {
                        message: 'Patientez...'
                    }
                },
                translate: {
                    records: {
                        noRecords : "Pas de resultats correspondants"
                    }
                },
                // column sorting
                sortable: true,
                toolbar: {
                    placement: ["bottom"],
                    items: {
                        pagination: {
                            pageSizeSelect: [5, 10, 20, 30, 50]
                        }
                    }
                },
                //pagination: true,
                search: {
                    input: $('#generalSearch'),
                    delay: 500,
                },
                // columns definition
                columns: [
                    {
                        field: "checkbox",
                        title: "",
                        template: "{{id}}",
                        sortable: !1,
                        width: 20,
                        textAlign: "center",
                        selector: {
                            class: "kt-checkbox--solid"
                        }
                    },
                    {
                        field: 'id',
                        title: '#',
                        sortable: 'asc',
                        width: 40,
                        type: 'number',
                        selector: false,
                        textAlign: 'center',
                        template: function (row, index) {
                            return index+1;
                        }
                    },
                    {
                        field: 'nom',
                        title: 'Nom/Raison Social',
                    },
                    {
                        field: 'postnom',
                        title: 'Postnom/Sigle',
                        template : function (row, index) {
                            var data = row.postnom ? row.postnom : "<span class='btn btn-bold btn-sm btn-font-sm  btn-label-danger'>Pas de Postnom/Sigle </span>";
                            return data;
                        }
                    },
                    {
                        field: 'prenom',
                        title: 'Prenom/Autres',
                        template: function (row, index) {
                            var data = row.prenom ? row.prenom : "<span class='btn btn-bold btn-sm btn-font-sm  btn-label-danger'>Pas de prénom et autres</span>";
                            return data;
                        }
                    },
                    {
                        field: 'email',
                        title: 'Email',
                        template: function (row, index) {
                            var data = row.email ? row.email : "<span class='btn btn-bold btn-sm btn-font-sm  btn-label-danger'>Pas d'Email</span>";
                            return data;
                        }
                    },
                    {
                        field: 'adresse',
                        title: 'Adresse/Siège Social',
                        width: 180,
                        template: function(row, index, datatable) {
                            return row.adresse;
                        }
                    },
                    {
                        field: 'telephone',
                        title: 'Numero de telephone',
                        template: function (row, index) {
                            var data = row.telephone ? row.telephone : "<span class='btn btn-bold btn-sm btn-font-sm  btn-label-danger'>Pas de telephone</span>";
                            return data;
                        }
                    },
                    {
                        field: 'Actions',
                        title: 'Actions',
                        sortable: false,
                        width: 130,
                        overflow: 'visible',
                        textAlign: 'center',
                        template: function(row, index, datatable) {
                            var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';
                            return '<div class="dropdown ' + dropup + '">\
                            <a href="#" class="btn btn-hover-success btn-icon btn-pill" id="btn-see-personne" data-id="' + row.id + '" title="Voir Détails">\
                                <i class="la la-eye"></i>\
                            </a>\
                            <a href="/personnes/editer/'+row.id+'" class="btn btn-hover-brand btn-icon btn-pill edit-personne" data-id="' + row.id + '" title="Modifier details">\
                                <i class="la la-edit"></i>\
                            </a>\
                            <a href="#" class="btn btn-hover-danger btn-icon btn-pill remove-personne" data-id="' + row.id + '" title="Supprimer détails">\
                                <i class="la la-trash"></i>\
                            </a>';
                        }
                    }
                ]
            };
            datatable = $('#kt_datatable').KTDatatable(options);
            $('#kt_form_type').on('change', function (){
                datatable.search($(this).val().toLowerCase(), "type");
            });
            // Handle view Detail
            $('#kt_datatable').on('click', '#btn-see-personne', function (){
                var id_personne = $(this).data('id');
                var personne = personnes.filter(function(element){
                    return element.id === id_personne
                });
                personne = personne[0];
                var buildFichePersonne      = function (personne) {
                    var html = `
                        <div class="kt-portlet__body">
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Nom/Raison Social:</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">${personne.nom}</span>
                                </div>
                            </div>
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Postnom/Sigle:</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">${personne.postnom}</span>
                                </div>
                            </div>
                        `;
                    if (personne.prenom) {
                        html += `
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Prénom/Autres:</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">
                                        ${personne.prenom}
                                    </span>
                                </div>
                            </div>
                        `;
                    }
                    if (personne.adresse) {
                        html += `
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Adresse/Siège Social:</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">
                                        ${personne.adresse}
                                    </span>
                                </div>
                            </div>
                        `; 
                    }
                    if (personne.email) {
                        html += `
                        <div class="form-group form-group-xs row">
                            <label class="col-4 col-form-label">Email:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext kt-font-bolder">
                                    ${personne.email}
                                </span>
                            </div>
                        </div>
                        `;
                    }
                    if ( personne.telephone) {
                        html += `
                        <div class="form-group form-group-xs row">
                            <label class="col-4 col-form-label">Numéro de telephone:</label>
                            <div class="col-8">
                                <span class="form-control-plaintext kt-font-bolder">${personne.telephone}</span>
                            </div>
                        </div>
                        `; 
                    }
                    if ( personne.nif) {
                        html += `
                        <div class="form-group form-group-xs row">
                            <label class="col-4 col-form-label">Numéro d'identification fiscale</label>
                            <div class="col-8">
                                <span class="form-control-plaintext kt-font-bolder">${personne.nif}</span>
                            </div>
                        </div>
                        `; 
                    }
                    if (personne.formeJuridique === "physique") {
                        html += `
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Etat Civil:</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">
                                        ${personne.etatCivil}
                                    </span>
                                </div>
                            </div>
                        `;
                    } else {
                        if (personne.secteur_activites) {
                            html += `
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Secteur d'activité:</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">
                                        ${personne.secteur_activites}
                                    </span>
                                </div>
                            </div>
                            `;
                        }
                        if (personne.numeroIdNationale) {
                            html += `
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Numéro d'identification National</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">
                                        ${personne.numeroIdNationale}
                                    </span>
                                </div>
                            </div>`;
                        }
                        if (personne.numeroRegistreCommerce) {
                            html += `
                            <div class="form-group form-group-xs row">
                                <label class="col-4 col-form-label">Numéro de registre de commerce</label>
                                <div class="col-8">
                                    <span class="form-control-plaintext kt-font-bolder">
                                    ${personne.numeroRegistreCommerce}
                                    </span>
                                </div>
                            </div>
                        `;
                        }
                    }
                    html += `</div>`;

                    return html; 
                }
                if (personne) {
                    var html = buildFichePersonne(personne);
                    $('#detailsBox h5#title').html(`Personne ID #${personne.id}`);
                    $('#detailsBox .modal-body').html(html);
                    $('#detailsBox').modal();
                }

            });
            // Handle Edit Detail
            $('#kt_datatable').on('click', '.edit-personne', function (){
                //console.log($(this).data('id'));
            });
            // Remove Detail
            $('#kt_datatable').on('click', '.remove-personne', function (){
                var id_personne =   $(this).data('id');
                var url         =   "/personnes/supprimer/"+id_personne;
                var self = $(this);
                //$(this).parents('tr').remove();
                bootbox.confirm({
                    message: "Etes vous sûre de vouloir supprimer cette personne",
                    buttons: {
                        confirm: {
                            label: 'Oui',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Non',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if ( result ) {
                            $.ajax({
                                method: "GET",
                                url: url
                            }).done(function( msg ) {
                                if (msg.deleted) {
                                    self.parents('tr').remove();
                                    toastr.success("Cette personne a été supprimé");
                                }
                            });
                        }
                    }
                });
            })

        }
    }
    var KTDatatableJsonRemoteDemo = {
        init: function (datajson) {
            var datatable;
            var options = {
                data: {
                    type: 'local',
                    source: datajson.entites,
                    pageSize: 5
                },
                // layout definition
                layout: {
                    scroll: !1,
                    footer: !1,
                    spinner: {
                        message: 'Patientez...'
                    }
                },
                translate: {
                    records: {
                        noRecords : "Pas de resultats correspondants"
                    },
                    toolbar: {
                        pagination: { 
                            items: { 
                                info: 'Afficher {{end}} elements'
                            }
                        }
                    }
                },
                // column sorting
                sortable: !0,
                toolbar: {
                    placement: ["bottom"],
                    items: {
                        pagination: {
                            pageSizeSelect: [5, 10, 20, 30, 50]
                        }
                    }
                },
                //pagination: true,
                search: {
                    input: $('#generalSearch'),
                    delay: 500,
                },
                // columns definition
                columns: [
                    {
                        field: "checkbox",
                        title: "",
                        template: "{{id}}",
                        sortable: !1,
                        width: 10,
                        textAlign: "center",
                        selector: {
                            class: "kt-checkbox--solid"
                        }
                    },
                    {
                        field: "intitule",
                        title: "Libelle Entité",
                        sortable: 'asc',
                        textAlign: "center",
                        template: function (row, index) {
                            return `${row.intitule} (${row.typeentite})`; 
                        }
                    },
                    {
                        field: 'intitule_mere',
                        title: 'Libelle Entité Mère',
                        sortable: 'asc',
                        textAlign: 'center',
                        template: function (row, index) {
                            /*if (row.intitule_mere && row.typeentite_mere) {
                                return `<strong class="kt-font-bold kt-font-danger">Pas d'entité mère</strong>`;
                            }*/
                            return `${row.intitule_mere} (${row.typeentite_mere})`;
                        }
                    },
                    {
                        field: 'Actions',
                        title: 'Actions',
                        textAlign: 'center',
                        template: function(row, index, datatable) {
                            var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';
                            return '<div class="dropdown ' + dropup + '">\
                            <a href="#" class="btn btn-brand btn-elevate btn-pill" id="btn-see-teritorial" data-idEntite="' + row.idEntite + '" data-tea="'+ row.typeEntiteA +'" title="Voir le territorial">\
                                <i class="la la-check-circle no-padding"></i>\
                            </a>';
                        }
                    }
                ]
            };
            $('#datatable').css('display', 'block'); 
            datatable               = $('#datatable').KTDatatable(options);
            let type_territorial    = datajson.id_territorial;
            // reload DataTable
            $("#datatable").KTDatatable("reload");
            let last_parent; 
            let next_child;  
            // Récupérer les détails d'un territorial
            $('#datatable').on('click', '#btn-see-teritorial', function (){
                
                // Création d'une tabulation 
                $('.nav-link.territorial').removeClass('active'); 
                $('#kt_tabs_7_1').removeClass('active'); 
                $('#kt_tabs_7_2').addClass('active');    
                if (!$('.nav-link.details').length) {
                    
                    var li = `
                    <li class="nav-item">
                        <a class="nav-link details active" data-toggle="tab" href="#kt_tabs_7_2" role="tab" aria-selected="true">
                            Détails
                        </a>
                    </li>
                    `; 
                    $('.navigation ul').append(li); 
                }   
                $('.nav-link.details').addClass('active');
                $('#kt_tabs_7_2 .loader-search').removeClass('hidden'); 
                
                if (!$('#kt_tabs_7_2 form').hasClass('territorial-form')) {
                    $('#kt_tabs_7_2 form').addClass('territorial-form'); 
                }
                if ($('#kt_tabs_7_2').hasClass('active')) {
                    // $('.btn-valid').removeClass('hidden');
                }
                var id_tea      =   $(this).data('tea'); 
                var identite    =   $(this).data('identite'); 

                $.ajax({
                    method: "POST",
                    url: '/personne/adresse/parents', 
                    data : {
                        id_tea,
                        identite,
                        type_territorial
                    }
                }).done(function( data ) {
                    const entites     = data.entites; 
                    last_parent = entites[0]; 
                    buildFormulaireParent(entites);
                    getChild(last_parent, type_territorial); 
                    $('#kt_tabs_7_2 form select').html('');
                    $('#kt_tabs_7_2 form .btn-add-entite').css('display', 'block');
                    $('#kt_tabs_7_2 form select').append(`<option value="">---</option>`); 
                    $.each(entites, function(index, item) {
                        const option = `<option value="${item.idEntiteAdmin}">${item.libelleEntiteAdmin}</option>`;
                        let attr_name = item.libelleTypeEntite.toLowerCase();
                        if (attr_name === 'avenue/rue') {
                            attr_name = 'avenue';
                        }
                        $('select[name="' +attr_name+ '"]').html('');
                        $('select[name="' +attr_name+ '"]').append(option);
                        $('select[name="' +attr_name+ '"]').attr('data-tea', item.fkTypeEntite).attr('data-entite-mere', item.fkEntiteMere);
                        $('#kt_tabs_7_2 form').find('.'+attr_name).find('.btn-add-entite').css('display', 'none');
                    });
                    $('#kt_tabs_7_2 .loader-search').addClass('hidden'); 
                    $('#kt_tabs_7_2 form').removeClass('territorial-form');
                });
            });

            // Fonction faisant appel aux enfants 
            const getChild = function ( parent, type_territorial ) {
                const libelle   = parent.libelleTypeEntite.toLowerCase();
                const id_entite_mere = parent.idEntiteAdmin; 
                const tea            = parent.fkTypeEntite; 
                $.ajax({
                    method: "POST",
                    url: '/personne/adresse/child', 
                    data : {
                        id_entite_mere,
                        type_territorial, 
                        tea
                    }
                }).done(function( data ) {
                    const entites     = data.entites;
                    console.log(entites.length);
                    if (entites.length) {
                        buildFormulaireChildren(entites);
                    } else {
                        console.log('No Entites');
                        $('.btn-valid').removeClass('hidden');
                    }
                    let fields = [];
                    $('#territorial-form select').each(function (index, item) {
                        fields.push($(this).attr('name'));
                    });

                    localStorage.setItem('fields', JSON.stringify(fields));
                });
            }

            // Construire le formulaire du détails territorial
            const buildFormulaireParent = function ( entites ) {
                const entitesParents = entites.reverse();
                $('#territorial-form').empty('');
                $.each(entitesParents, function ( index, item) {
                    let type = item.libelleTypeEntite.toLowerCase();
                    if (type === 'avenue/rue') {
                        type = 'avenue';
                    }
                    const select = `
                        <div class="form-group row ${type}">
                            <label class="col-xl-2 col-lg-2 col-form-label">${item.libelleTypeEntite}</label>
                            <div class="col-lg-9 col-xl-8">
                                <select name="${type}" id="${type}" class="form-control" data-tea="${item.fkTypeEntite}" required>
                                    <option value="">--</option>
                                </select>
                            </div>
                            <button title="Ajouter l'entite" class="btn btn-brand btn-elevate btn-pill btn-add-entite" data-type="${type}">
                                <i class="la la-plus-circle no-padding"></i>                            
                            </button>
                        </div>
                    `;
                    $('#territorial-form').append(select);
                }); 
            }

            const buildFormulaireChildren   = function (children) {
                $.each(children, function ( index, item) {
                    let type = item.libelleTypeEntite.toLowerCase();
                    if (type === 'avenue/rue') {
                        type = 'avenue';
                    }
                    const select = `
                        <div class="form-group row ${type}">
                            <label class="col-xl-2 col-lg-2 col-form-label">${item.libelleTypeEntite}</label>
                            <div class="col-lg-9 col-xl-8">
                                <select name="${type}" id="${type}" class="form-control" data-tea="${item.type_entite}" required>
                                    <option value="">--</option>
                                </select>
                            </div>
                            <button title="Ajouter l'entite" class="btn btn-brand btn-elevate btn-pill btn-add-entite" data-type="${type}">
                                <i class="la la-plus-circle no-padding"></i>                            
                            </button>
                        </div>
                    `;
                    $('#territorial-form').append(select);
                    if (item.entitesAdministratives && item.entitesAdministratives.length) {
                        let option;
                        $.each(item.entitesAdministratives, function (index, item){
                            option += `<option value="${item.IdEntite}">${item.IntituleEntite}</option>`;
                        })
                        $('select[name="' +type+ '"]').append(option);
                    }
                });
            }

            const getChildAfterChange = function (parent) {
                const type_territorial      = $('select#form_origine').val();
                const tea                   = parent.typeentite;
                const id_entite_mere        = parent.idEntiteAdmin;
                const fields                = JSON.parse(localStorage.getItem('fields'));
                const libelle               = parent.libelleTypeEntite.toLowerCase();
                let index                   = fields.indexOf(libelle);
                let child                   = fields[index+1];
                if (child === 'avenue/rue') {
                    child = 'avenue';
                }
                $.ajax({
                    method: "POST",
                    url: '/personne/adresse/child',
                    data : {
                        id_entite_mere,
                        type_territorial,
                        tea,
                        "direct": "direct"
                    }
                }).done(function( data ) {
                    const entites     = data.entites;
                    if (entites.length) {
                        $.each(entites, function(index, item) {
                            const option = `<option value="${item.IdEntite}">${item.IntituleEntite}</option>`;
                            $('select[name="' +child+ '"]').append(option);
                        });
                    } else {
                        $('.btn-valid').removeClass('hidden');
                    }
                });
            }

            const resetTab = function () {
                $('div.navigation .nav').empty(); 
                $('#kt_tabs_7_1').addClass('active'); 
                $('#kt_tabs_7_2').removeClass('active'); 
            }
            // Lancer la recherche selon idEA lors du changement de valeur
            $('#kt_tabs_7_2 form').on('change', 'select', function(event) {
                const name = $(this).attr('name');
                const parent = {
                    libelleTypeEntite: name.toUpperCase(),
                    typeentite: $(this).data('tea'),
                    idEntiteAdmin: event.target.value
                };

                getChildAfterChange(parent);
                if (name == "avenue" && $('input[name="numero"]').length == 0 ) {
                    const numero = `
                        <div class="form-group row numero">
                            <label class="col-xl-2 col-lg-2 col-form-label">Numéro</label>
                            <div class="col-lg-7 col-xl-7">
                                <input name="numero" id="numero" class="form-control" required />
                            </div>
                        </div>
                    `;
                    $('#kt_tabs_7_2 #territorial-form').append(numero);
                }
            });

            $('#territorial-form').on('click', '.btn-add-entite', function (event){
                event.preventDefault();
                $('#add-entite').modal();
                const self  =   $(this);
                const type  =   self.data('type');

                const tea   =   $('select[name="' +type+ '"]').data('tea');

                const fields    = JSON.parse(localStorage.getItem('fields'));

                let index = fields.indexOf(type) - 1;

                let field   = fields[index];

                const entite_mere  =   $('select[name="' +field+ '"]').val();

                const sel   =   document.getElementById(field);

                const option    = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex] : "" ;

                $('form.add-entite input[name="libelle_Entite_mere"]').val(option.text);
                $('form.add-entite input[name="type_ea"]').val(tea);
                $('form.add-entite input[name="idEntiteMere"]').val(entite_mere);
                $('form.add-entite input[name="type"]').val(type);
            });

            $('.btn-valid').on('click', function (event){
                event.preventDefault();

                const fields = JSON.parse(localStorage.getItem('fields'));

            })
            
            $('.origine-form').on('submit', function (event){
                console.log('hello'); 
            });

            $('.btn-add-entite').on('click', function (event) {
                event.preventDefault();

                const id_entite_mere = $('input[name="idEntiteMere"]').val();
                const libelle        = $('input[name="libelle"]').val();
                const tea            = $('input[name="type_ea"]').val();
                const url            = '/personne/ajouter/entite';
                const type           = $('input[name="type"]').val();

                bootbox.confirm({
                    message: "Etes vous sûre de vouloir ajouter cette entité administrative",
                    buttons: {
                        confirm: {
                            label: 'Oui',
                            className: 'btn btn-brand'
                        },
                        cancel: {
                            label: 'Non',
                            className: 'btn btn-secondary'
                        }
                    },
                    callback: function (result) {
                        if ( result ) {
                            $.ajax({
                                method: "POST",
                                url: url,
                                data: {
                                    id_entite_mere,
                                    libelle,
                                    tea
                                }
                            }).done(function( data ) {
                                const new_id = data.entite.IdEntite;
                                $('#territorial-form select[name="' +type+ '"]').append(`<option value="${new_id}">${libelle}</option>`);
                            });
                        }
                        $('#add-entite').modal('hide');
                        $('input[name="libelle"]').val("");
                    }
                });

            })
        }
    }
    $(document).ready(function (){
        if ($('#personne_telephone').length) {
            var input = document.querySelector("#personne_telephone");
            window.intlTelInput(input, {
                preferredCountries: ['cd'],
                utilsScript: "node_modules/intl-tel-input/build/js/utils.js"
            });
        }
        $('#personne_physique_save, #personne_morale_save').on('click', function (event) {
            $(this).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
        });
        $('.edit-btn').on('click', function (){
            if ($('.add-fonction').length) {

                $('.add-fonction').hide();
                $('.edit-fonction').show();

                var id     = $(this).data('id');
                var nom    = $(this).data('nom');

                $('.edit-fonction').find('#fonction_nom').val(nom);
                $('.edit-fonction').find('#fonction_id_fonction').val(id);
            }

            if ($('.add-activite').length) {
                $('.add-activite').hide();
                $('.edit-activite').show();

                var id     = $(this).data('id');
                var nom    = $(this).data('nom');

                $('.edit-activite').find('#activite_nom').val(nom);
                $('.edit-activite').find('#activite_id_activite').val(id);
            }
        });
        $('#fonction_reset').on('click', function (){
            $('.add-fonction').show();
            $('.edit-fonction').hide();
        });
        $('#activite_reset').on('click', function () {
            $('.add-activite').show();
            $('.edit-activite').hide();
        })
        $('.btn-remove').on('click', function (){
            var id_fonction =   $(this).data('id');
            var url         =   "/admin/fonctions/remove/"+id_fonction;
            var self = $(this);
            //$(this).parents('tr').remove();
            bootbox.confirm({
                message: "Etes vous sûre de vouloir supprimer cette fonction",
                buttons: {
                    confirm: {
                        label: 'Oui',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Non',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if ( result ) {
                        $.ajax({
                            method: "GET",
                            url: url
                        }).done(function( msg ) {
                            if (msg.deleted) {
                                self.parents('tr').remove();
                                toastr.success("Cette fonction a été supprimé");
                            }
                        });
                    }
                }
            });
        });

        $('.btn-remove-physique').on('click', function (){
            var id          =   $(this).data('personne-id');
            var url         =   "/personnes/physiques/supprimer/"+id;
            var self        = $(this);
            //$(this).parents('tr').remove();
            bootbox.confirm({
                message: "Etes vous sûre de vouloir supprimer cette personne",
                buttons: {
                    confirm: {
                        label: 'Oui',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Non',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if ( result ) {
                        $.ajax({
                            method: "GET",
                            url: url
                        }).done(function( msg ) {
                            if (msg.deleted) {
                                self.parents('tr').remove();
                                toastr.success("Cette personne a été supprimé");
                            }
                        });
                    }
                }
            });
        });

        if( $('#kt_datatable').length ) {
            if ($('#kt_datatable').hasClass("admin-list")) {
                setTimeout(function () {
                    KTDatatableRemoteAjaxDemo.init(true);
                }, 1500);
            }
            setTimeout(function () {
                KTDatatableRemoteAjaxDemo.init();
            }, 1500);
        }
        var initCalendar = $('#personne_date_de_naissance').length && $('#personne_date_de_naissance').datepicker({ format: "yyyy-m-d" });

        if($('#personne_forme_juridique').length) {
            var type_form = localStorage.getItem('type');
            if (type_form == 'physique') {
                $('.physiques-class').show();
                $('.morales-class').hide();
            } else {
                $('.physiques-class').hide();
                $('.morales-class').show();
            }
            $('#personne_forme_juridique').on('change', function (event) {
                var type  =   $(this).val();
                localStorage.setItem('type', type);
                if (type == 'physique') {
                    $('.physiques-class').show();
                    $('.morales-class').hide();
                } else {
                    $('.physiques-class').hide();
                    $('.morales-class').show();
                }
            })

            if($('#personne_forme_juridique').val() == 'physique') {
                $('.physiques-class').show();
                $('.morales-class').hide();
            } else {
                $('.physiques-class').hide();
                $('.morales-class').show();
            }
        }

        if($('.ajouter-personne').length) {
            $('.btn-check-nrc').on("click", function () {
                var self = $(this);
                $(this).addClass('kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light');
                var nrc = $('#personne_numero_registre_commerce').val();
                $.ajax({
                    method: "GET",
                    url: "/personne/check/nrc/"+nrc
                }).done(function( msg ) {

                    self.removeClass("kt-spinner kt-spinner--right kt-spinner--md kt-spinner--light");
                });
            })
        }
        if ($('#liste_activites').length) {
            $('#liste_activites').DataTable({
                "language": {
                    "lengthMenu": "Afficher _MENU_ activités par page",
                    "zeroRecords": "Aucun agent trouvé",
                    "info": " Page _PAGE_ / _PAGES_",
                    "infoEmpty": "Pas d'activité trouvé",
                    "infoFiltered": "(filtrer sur _MAX_ total agents)"
                }
            });
        }
        $('.open-adress').on('click', function (event){
            event.preventDefault(); 
            var options = ``;

            $('.loader').addClass('flex-class');
            if (!$('select#form_origine option').length) {
                $.ajax({
                    method: "GET",
                    url: "/personne/adresse/type_territorial"
                }).done(function( msg ) {
                    var allOptions = msg.types; 
                    
                    $.each(allOptions, function(index, item) { 
                        options += `<option value="${item.idtypeterritorial}">${item.intituleterritorial}</option>`;
                    }); 
                    $('select#form_origine').append(options); 
                });
            }
            setTimeout(function(){
                $('.loader').removeClass('flex-class');
                $('#adresse').modal();
            }, 1500); 
        }); 

        const resetTab = function () {
            $('div.navigation .nav').empty(); 
            $('#kt_tabs_7_1').addClass('active'); 
            $('#kt_tabs_7_2').removeClass('active'); 
            $('#datatable').css('display', 'none'); 
        }

        const capitalize = function (word) {
            return word.charAt(0).toUpperCase() + word.slice(1);
        }

        $('.search-btn').on('click', function (event){
            event.preventDefault(); 
            var search = $('input[name="entite"]'); 
            if (!search.val()) {
                search.addClass('is-invalid'); 
                $('.search-feedback').css('display', 'block !important');
                return; 
            }
            search.removeClass('is-invalid'); 
            $('.search-feedback').css('display', 'none !important');
            resetTab(); 

            $('.loader-search').removeClass('hidden'); 

            var search              = $("input[name='entite']").val();
            var idtypeterritorial   = $('select#form_origine').val();  

            $.ajax({
                method: "POST",
                url: "/personne/adresse/entite_administratives", 
                data : {
                    'search': search, 
                    'type_territorial': idtypeterritorial
                }
            }).done(function( msg ) { 
                if (!$('.nav-link.territorial').length) {
                    var li = `
                        <li class="nav-item">
                            <a class="nav-link territorial active" data-toggle="tab" href="#kt_tabs_7_1" role="tab" aria-selected="true">
                                Territorial
                            </a>
                        </li>
                    `; 
                    $('.navigation ul').append(li); 
                }

                $("#datatable").KTDatatable("destroy"); 
                KTDatatableJsonRemoteDemo.init(msg);
                $('.loader-search').addClass('hidden');
            });
        });

        $('.btn-add-entite').on('click', function (event){
            event.preventDefault();
            $('#add-entite').modal();
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            console.log(e.target); // newly activated tab
            console.log(e.relatedTarget);  // previous active tab
        });

        if ($('#territorial-form').length) {
            $('#territorial-form').validate({
                messages: {
                    province : "Ce champ est obligatoire",
                    territoire : "Ce champ est obligatoire",
                    ville: "Ce champ est obligatoire",
                    cité: "Ce champ est obligatoire",
                    secteur: "Ce champ est obligatoire",
                    village: "Ce champ est obligatoire",
                    commune: "Ce champ est obligatoire",
                    quartier: "Ce champ est obligatoire",
                    avenue: "Ce champ est obligatoire",
                    numero: "Ce champ est obligatoire"
                }
            });
        }

        $('.btn-valid').on('click', function (event){
            event.preventDefault();

            const isValid   =   $('#territorial-form').valid();

            if ( isValid ) {

                const adresses_fields = JSON.parse(localStorage.getItem('fields'));
                let full_adress = '';
                let data        = new Object();
                const prop      = adresses_fields[adresses_fields.length - 1];

                $.each(adresses_fields, function (index, item) {
                    const select = document.getElementById(item);
                    const option = select.options[select.selectedIndex] ? select.options[select.selectedIndex] : "";
                    full_adress += `${capitalize(item)} ${option.text}, `;
                    if (prop == item) {
                        data["fk_entite"] = option.value;
                    }
                });

                if ($('input[name="numero"]').length) {
                    const number = $('input[name="numero"]').val();
                    full_adress += `Numero ${number}`;
                    data["numero"]  = number;
                    $('#personne_numero').val(data["numero"]);
                } else {
                    full_adress = full_adress.substr(0, full_adress.length-2);
                }
                data["adresse_complete"] = full_adress;

                $('#personne_adresse').val(full_adress);
                $('#personne_fk_entite').val(data["fk_entite"]);

                resetTab();

                $('.loader').removeClass('flex-class');

                $('#adresse').modal('hide');
            }

        });
         
    });
})(jQuery)