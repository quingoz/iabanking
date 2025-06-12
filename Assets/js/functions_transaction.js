document.addEventListener('DOMContentLoaded',function(){

    // Define qué columnas ocultar según el tipo de usuario
    let hiddenColumns = [];

    if (typeUser == 1) {
        hiddenColumns = [4, 7, 9]; // Por ejemplo: bank, account, client_id
    } else if (typeUser == 2) {
        hiddenColumns = [1, 2, 7, 8]; // Por ejemplo: client_name, amount
    }

	tableTransaction = $('#transaction-list-table').DataTable( { 
        "aProcessing":true,
        "aServerSide":true,
        "language": {           
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": ">",
                "previous": "<"
            },
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros"
        },
        "ajax":{
            "url": " "+base_url+"/transaccion/getMovimientos",
            "data":function (d) {
                // incluir filtros personalizados
                d.bank = $('#filtroBank').val();
                d.account = $('#filtroAccount').val();
                d.reference = $('#filtroReference').val();
                d.date = $('#filtroDate').val();
            }
        },
        "deferLoading": 0, 
        "columns":[
            {"data":"id"}, //0
            { data: 'bank' }, //1
            { data: 'account' }, //2
            { data: 'reference' }, //3
            { data: 'client_name' }, // 4
            { data: 'date' }, // 5
            {
                "data": "amount",
                "render": function(data, type, row) {
                    let color = parseFloat(data) >= 0 ? 'green' : 'red';
                    return '<span style="color:' + color + ';">' + data + '</span>';
                }
            }, // 6
            { data: 'client_id' }, // 7
            { data: 'responsible'}, // 8
            { data: 'tasa' }, // 9
			{
                "data": "id",
                "render": function(data, type, row) {
                    let html = '';
                    html += `<div class="flex align-items-center list-user-action" bis_skin_checked="1">
								 <a class="btn btn-sm btn-icon btn-danger ms-2" data-bs-toggle="tooltip" data-bs-placement="top" href="javascript:void(0)" onclick="confirmDelete(${data})" aria-label="Eliminar" data-bs-original-title="Eliminar">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    
                                            <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                                            <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                
                                        </svg>                            
                                    </span>
                                 </a>
                              </div>`        
                    return html;
                }
            }
        ],
        columnDefs: [
            { targets: hiddenColumns, visible: false } // Ocultar bank, account, client_id
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 50,
        "order":[[0,"asc"]]  
    });

    // Para los select
    $('#filtroBank, #filtroAccount, #filtroDate').on('change', function () {
        tableTransaction.ajax.reload();
    });

   // Filtro de texto (reference) sí puede usar input + change
    $('#filtroReference').on('input change', function () {
        tableTransaction.ajax.reload();
    });
    
    $('#transaction-list-table').on('xhr.dt', function () {
        const data = tableTransaction.ajax.json().data;

        // Obtener valores seleccionados actualmente
        const selectedBank = $('#filtroBank').val();
        const selectedAccount = $('#filtroAccount').val();

        // Obtener valores únicos
        const banks = [...new Set(data.map(item => item.bank))];
        const accounts = [...new Set(data.map(item => item.account))];

        // Actualizar filtroBank
        const selectBank = $('#filtroBank').empty().append('<option value="">Todos</option>');
        banks.forEach(bank => {
            if (bank) {
                const selected = bank === selectedBank ? 'selected' : '';
                selectBank.append(`<option value="${bank}" ${selected}>${bank}</option>`);
            }
        });

        // Actualizar filtroAccount
        const selectAccount = $('#filtroAccount').empty().append('<option value="">Todas</option>');
        accounts.forEach(account => {
            if (account) {
                const selected = account === selectedAccount ? 'selected' : '';
                selectAccount.append(`<option value="${account}" ${selected}>${account}</option>`);
            }
        });
    });

    const editableColumns = {
        reference: 3,
        client: 4, // Aquí editas cliente en vez de responsable
        date: 5,
        amount: 6,
        tasa: 9
    };

    $('#transaction-list-table tbody').on('dblclick', 'td', function () {
        
		
		
		const cell = tableTransaction.cell(this);
        const rowData = tableTransaction.row(this).data();
        const columnIndex = cell.index().column;
		
        const columnName = Object.keys(editableColumns).find(key => editableColumns[key] === columnIndex);
	
        if (!columnName) return;
        const currentValue = cell.data();
		
        if (columnName === 'client') {
            if ($(this).find('select').length > 0) return;
            $(this).html(`<select class="form-control form-control-sm select-client" style="width: 100%"></select>`);
            const select = $(this).find('select');
			console.log(base_url + '/transaccion/listado');
            select.select2({
                ajax: {
                    url: base_url + '/transaccion/listado',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ search: params.term || '' }),
                    processResults: data => ({
                        results: data.map(cliente => ({
                            id: cliente.id,
                            text: cliente.name
                        }))
                    }),
                    cache: true
                },
                placeholder: 'Seleccionar cliente',
                minimumInputLength: 0,
                width: 'resolve'
            });

            // Forzar búsqueda vacía al abrir
            select.on('select2:open', () => {
                $('.select2-search__field').trigger('input');
            });

            // Establecer valor actual
            const currentId = rowData.client_id;
            if (currentId) {
                const option = new Option(currentValue, currentId, true, true);
                select.append(option).trigger('change');
            }

            select.on('select2:select', function (e) {
                const selected = e.params.data;
                updateTransactionField(rowData.id, 'id_cliente', selected.id, cell, selected.text);
            });

            select.on('select2:close', function () {
                if (!select.val()) {
                    cell.data(currentValue).draw(); // Revertir
                }
            });

            select.focus();
            return;
        }

        // Inputs para reference, date, amount
        const inputType = (columnName === 'date') ? 'date' : 'text';
        if ($(this).find('input').length > 0) return;

        $(this).html(`<input type="${inputType}" class="form-control form-control-sm" value="${currentValue}" />`);
        const input = $(this).find('input');
        input.focus();

        input.on('blur keyup', function (e) {
            if (e.type === 'blur' || (e.type === 'keyup' && e.key === 'Enter')) {
                const newValue = input.val();
                if (newValue !== currentValue) {
                    updateTransactionField(rowData.id, columnName, newValue, cell);
                } else {
                    cell.data(currentValue).draw();
                }
            }
        });
    });


    if(document.querySelector("#formNewTransaction")){
		let formNewTransaction = document.querySelector("#formNewTransaction");
		formNewTransaction.onsubmit = function(e) {
			e.preventDefault();

			let strAnio = document.querySelector('#anio').value;
            let strMes = document.querySelector('#mes').value;
            let strBanco = document.querySelector('#banco').value;
            let strArchive = document.querySelector('#archive').value;

			if(strAnio == "" || strMes == "" || strBanco == "" || strArchive == "")
			{
				Swal.fire("Por favor", "Todos los campos son obligatorios.", "error");
				return false;
			}else{

				// Mostrar loader
				let divLoading = document.querySelector("#loading-content");
				divLoading.classList.remove('d-none');

				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url+'/transaccion/setTransaction'; 
				var formData = new FormData(formNewTransaction);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
					if(request.readyState != 4) return;

					// Ocultar loader
					divLoading.classList.add('d-none');

					if(request.status == 200){
						var objData = JSON.parse(request.responseText);
						if(objData.status)
						{	
							
							//Swal.fire('Atención',objData.msg,'error');
							window.location = base_url+'/transaccion';
							//console.log(objData.msg);
						}else{
							
							Swal.fire('Atención',objData.msg,'error');
							//console.log(objData.msg);
						}
					}else{
						Swal.fire("Atención","El archivo no se proceso de manera correcta", "warning");
						console.warn("Advertencia: Probablemente no hay creditos para generar el archivo.");
					}
					divLoading.style.display = "none";
					return false;
				}
			}
		}
	}

	if(document.querySelector("#formEditTransaccion")){
		let formEditTransaccion = document.querySelector("#formEditTransaccion");
		formEditTransaccion.onsubmit = function(e) {
			e.preventDefault();

            console.log('presiono submit');

			let reference = document.querySelector('#reference').value;
            let date = document.querySelector('#date').value;
            let amount = document.querySelector('#amount').value;

			if(reference == "" || date == "" || amount == "")
			{
				Swal.fire("Por favor", "Todos los campos son obligatorios.", "error");
				return false;
			}else{
				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url+'/transaccion/updateTransaccion'; 
				var formData = new FormData(formEditTransaccion);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
					if(request.readyState != 4) return;
					if(request.status == 200){
						var objData = JSON.parse(request.responseText);

						if(objData.status)
						{   
                            Swal.fire('Éxito', objData.message, 'success').then((result) => {
                                if (result.isConfirmed) {
                                    window.location = base_url+'/transaccion';
                                }
                            });
						}else{
							Swal.fire('Atención',objData.message,'error');
						}
					}else{
						Swal.fire("Atención","Error en el proceso", "error");
					}
					divLoading.style.display = "none";
					return false;
				}
			}
		}
	}

},false);

function confirmDelete(id) {

    Swal.fire({
        title: '¡Esta acción no se puede deshacer!',
        text: '¿Desea eliminar la transaccion?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3b8aff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Si, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí haces la petición
            fetch(base_url+`/transaccion/deleteTransaccion/${id}`, {
                method: 'GET', // o 'GET' si es tu caso
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Exito', data.message, 'success').then((result) => {
                        if (result.isConfirmed) {
                            $('#transaction-list-table').DataTable().ajax.reload(); 
                        }
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Ocurrió un error en la solicitud.', 'error');
            });
        }
    });
}


function updateTransactionField(id, field, value, cell, displayText = null) {
    fetch(base_url + '/transaccion/updateField', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id: id,
            field: field,
            value: value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            // Mostrar texto si viene (para responsable)
            cell.data(displayText ?? value).draw();
            Swal.fire('Actualizado', data.message, 'success');
        } else {
            Swal.fire('Error', data.message, 'error');
            cell.data(cell.data()).draw();
        }
    })
    .catch(() => {
        Swal.fire('Error', 'Ocurrió un error al actualizar', 'error');
        cell.data(cell.data()).draw();
    });
}
