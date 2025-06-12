
document.addEventListener('DOMContentLoaded',function(){

    let tableEnterprise = $('#enterprise-list-table').dataTable( { 
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
            "url": " "+base_url+"/enterprise/getEnterprises",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"name"},
            {"data":"bd"},
            {"data":"rif"},
            {"data":"token"},
            {"data":"table"},
            
            {
                "data": "id",
                "render": function(data, type, row) {
                    let html = '';
                    html += `<div class="flex align-items-center list-user-action" bis_skin_checked="1">
                                 <a class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-original-title="Edit" href="${base_url}/enterprise/edit/${data}" aria-label="Edit" data-bs-original-title="Edit">
                                    <span class="btn-inner">
                                       <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       </svg>
                                    </span>
                                 </a>`
                    if(row.status == 1){
                        html += `<a class="btn btn-sm btn-icon btn-danger ms-2" data-bs-toggle="tooltip" data-bs-placement="top" href="javascript:void(0)" onclick="confirmDelete(${data}, 0)" aria-label="Desactivar" data-bs-original-title="Desactivar">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    
                                            <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                                            <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                
                                        </svg>                            
                                    </span>
                                 </a>
                              </div>`
                    }else{
                        html += `<a class="btn btn-sm btn-icon btn-success ms-2" data-bs-toggle="tooltip" data-bs-placement="top" href="javascript:void(0)" onclick="confirmDelete(${data}, 1)" aria-label="Activar" data-bs-original-title="Activar">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    
                                            <path d="M12.0001 8.32739V15.6537" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    <path d="M15.6668 11.9904H8.3335" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6857 2H7.31429C4.04762 2 2 4.31208 2 7.58516V16.4148C2 19.6879 4.0381 22 7.31429 22H16.6857C19.9619 22 22 19.6879 22 16.4148V7.58516C22 4.31208 19.9619 2 16.6857 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                
                                        </svg>                            
                                    </span>
                                 </a>
                              </div>`
                    }
                                 
                    return html;
                }
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 50,
        "order":[[0,"asc"]]  
    });

    if(document.querySelector("#formEditEnterprise")){
		let formEditEnterprise = document.querySelector("#formEditEnterprise");
		formEditEnterprise.onsubmit = function(e) {
			e.preventDefault();

			let name = document.querySelector('#name').value;
            let bd = document.querySelector('#bd').value;
            let rif = document.querySelector('#rif').value;
            let token = document.querySelector('#token').value;

			if(name == "" || bd == "" || rif == "" || token == "")
			{
				Swal.fire("Por favor", "Todos los campos son obligatorios.", "error");
				return false;
			}else{
				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url+'/enterprise/updateEnterprise'; 
				var formData = new FormData(formEditEnterprise);
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
                                    window.location = base_url+'/enterprise';
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

function confirmDelete(id, status) {

    if(status == 0){
        botonText = "Si, desactivar";
        content = "¿Desea desactivar la empresa?";
    }else{
        botonText = 'Si, activar';
        content = "¿Desea activar la empresa?";
    }
    Swal.fire({
        title: '¡Esta acción no se puede deshacer!',
        text: content,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3b8aff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: botonText,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí haces la petición
            fetch(base_url+`/enterprise/deleteEnterprise/${id}`, {
                method: 'GET', // o 'GET' si es tu caso
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Actualizado', data.message, 'success').then((result) => {
                        if (result.isConfirmed) {
                            $('#enterprise-list-table').DataTable().ajax.reload(); 
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