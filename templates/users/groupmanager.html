{include file="header.tpl"}
			<script type="text/javascript">
				var root = '{$smarty.const.ROOT}/index.php';
				initialize = function($) {
					$('.datagrid tbody tr input').each(function(){
						if($(this).attr('checked')){
							$(this).parents('tr').addClass('checked');
						}
					});
					$('.datagrid tbody tr').mouseover(function(){
						$(this).addClass('active');
					}).mouseout(function(){
						$(this).removeClass('active');
					}).click(function(event){
						if($(event.target).attr('type') != 'checkbox')
							$(this).find('input').attr('checked', !$(this).find('input').attr('checked'));
						$(this).toggleClass('checked');
					});
					function clearChecks() {
						$('.datagrid tbody tr').removeClass('checked').find('input[type=checkbox]').attr('checked', null);
						$('#check_all_user').attr('checked', null);
					}
					$('#check_all_user').click(function(){
						$('.datagrid tbody tr').click();
					});
					$( "#edit_dialog" ).dialog({
						autoOpen: false,
						show: "blind",
						hide: "explode",
						width: 'auto',
						buttons: {
							submit: function(){
								$.post(root, {
									operation: 'usermanager',
									type: $('#edit_type').val(),
									id: $('#user_id').val(),
									username: $('#username').val(),
									email: $('#email').val(),
									status: $('#status').val(),
									format: 'json'
								}, function(){
									$('#edit_dialog').dialog('close');
									showMessage("{show text='Users are updated'}","{show text='Success'}", true);
								})
							}
						}
					});

					function checkIfNoGroupSelected() {
						if($('.datagrid tr.checked').length == 0) {
							showMessage("{show text='No group selected!'}", "{show text='Error'}");
							return true;
						}
					}

					$('#modify').click(function(){
						if($('.datagrid tr.checked').length > 1) {
							showMessage("{show text='More than one user selected!'}", "{show text='Error'}");
							return;
						}
						if(checkIfNoGroupSelected())
							return;
						var user = $('.datagrid tr.checked td');
						$('#username').val(user.eq(2).text());
						$('#email').val(user.eq(3).text());
						$('#user_id').val(user.eq(1).text());
						$('#status').val(user.eq(9).text());
						$('#edit_type').val('update');
						$('#edit_dialog').dialog('option', 'title', "{show text='Edit User - '}" + user.eq(2).text()).dialog('open');
					});
					function getUserIDs() {
						var userIds = [];
						$('.datagrid tr.checked').each(function(){
							userIds.push($(this).children('td').eq(1).text().trim());
						});
						return userIds;
					}

					$('#add').click(function(){
						if(checkIfNoGroupSelected())
							return;
						$('#edit_dialog').dialog('option', 'title', "{show text='Edit User - '}" + user.eq(2).text()).dialog('open');
					});
					$('#delete').click(function(){
						if(checkIfNoGroupSelected())
							return;
						$.post(root, {
							operation: 'usermanager',
							type: 'delete',
							format: 'json',
							ids: getUserIDs().join(',')
						}, function(){
							showMessage("{show text='Users are deleted'}","{show text='Success'}", true);
							clearChecks();
						});
					});
					$('#suspend').click(function(){
						if(checkIfNoGroupSelected())
							return;
						$.post(root, {
							operation: 'usermanager',
							type: 'suspend',
							format: 'json',
							ids: getUserIDs().join(',')
						}, function(){
							showMessage("{show text='Users are suspended'}","{show text='Success'}", true);
							clearChecks();
						});
					});
					$('#page').change(function(){
						$('#query_form').submit();
					});
				}
			</script>
			<div id="groups_result">
				<form id="query_form" action="{$smarty.const.ROOT}/index.php">
					<input type="hidden" name="operation" value="usermanager"/>
					<div id="groups_control" class="toolbar">
						<input type='button' id="add" class="button" value="{show text='Add'}"/>
						<input type='button' id="modify" class="button" value="{show text='Modify'}"/>
						<input type='button' id="delete" class="button" value="{show text='Delete'}"/>
					</div>
					<table class="datagrid" cellspadding='1'>
						<thead>
							<tr>
								<th>
									<input type="checkbox" id="check_all_user" />
								</th>
								<th>{show text='ID'}</th>
								<th>{show text='Group Name'}</th>
								<th>{show text='Description'}</th>
								<th>{show text='Creation Time'}</th>
								<th>{show text='Last Modification Time'}</th>
								<th>{show text='Creator'}</th>
							</tr>
						</thead>
						<tbody>
							{section name=group loop=$groups}
							<tr>
								<td>
									<input type="checkbox" name="{$groups[group].id}" />
								</td>
								<td>{$groups[group].id}</td>
								<td>{$groups[group].groupname}</td>
								<td>{$groups[group].description}</td>
								<td>{$groups[group].creation_time}</td>
								<td>{$groups[group].last_modification_time}</td>
								<td>{$groups[group].username}</td>
							</tr>
							{/section}
						</tbody>
						<tfoot>
							<tr height='5'>
								<td colspan='10'>
									{show text='Goto Page'}
									<select id="page" name="page">
										{section name=i loop=$page_count}
											<option value='{$i}'>{$i+1}</option>
										{/section}
									</select>
								</td>
							</tr>
						</tfoot>
					</table>
				</form>
				<div id="user_table">
					<div id="groups_control" class="toolbar">
						<input type='button' id="add" class="button" value="{show text='Add'}"/>
						<input type='button' id="delete" class="button" value="{show text='Delete'}"/>
					</div>
					<table class="datagrid" cellspadding='1'>
						<thead>
							<tr>
								<th>
									<input type="checkbox" id="check_all_user" />
								</th>
								<th>{show text='ID'}</th>
								<th>{show text='Username'}</th>
								<th>{show text='Email'}</th>
								<th>{show text='Register Time'}</th>
								<th>{show text='Register IP'}</th>
								<th>{show text='Last Login IP'}</th>
								<th>{show text='Last Login Time'}</th>
								<th>{show text='Login Count'}</th>
								<th>{show text='Status'}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr height='5'>
								<td colspan='10'>
									{show text='Goto Page'}
									<select id="page" name="page">
									</select>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div id="edit_dialog" title="{show text='Edit Group'}">
				<form action="{$smarty.const.ROOT}/index.php" class="form container">
					<dl>
						<dt>
							<label for="groupname">{show text='Group Name'}:</label>
						</dt>
						<dd>
							<input id="groupname" name="groupname" class="text_input" />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="description">{show text='description'}:</label>
						</dt>
						<dd>
							<input id="description" name="description" class="text_input" />
						</dd>
					</dl>
					<input type="hidden" id="edit_type" name="type"/>
					<input type="hidden" id="group_id" name="id"/>
				</form>
			</div>
{include file="footer.tpl"}
