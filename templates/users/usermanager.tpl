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

					function checkIfNoUserSelected() {
						if($('.datagrid tr.checked').length == 0) {
							showMessage("{show text='No user selected!'}", "{show text='Error'}");
							return true;
						}
					}

					$('#modify').click(function(){
						if($('.datagrid tr.checked').length > 1) {
							showMessage("{show text='More than one user selected!'}", "{show text='Error'}");
							return;
						}
						if(checkIfNoUserSelected())
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

					$('#activate').click(function(){
						if(checkIfNoUserSelected())
							return;
						$.post(root, {
							operation: 'usermanager',
							type: 'activate',
							format: 'json',
							ids: getUserIDs().join(',')
						}, function(){
							showMessage("{show text='Users are activated'}","{show text='Success'}", true);
							clearChecks();
						});
					});
					$('#delete').click(function(){
						if(checkIfNoUserSelected())
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
						if(checkIfNoUserSelected())
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
			<div id="users_result">
				<form id="query_form" action="{$smarty.const.ROOT}/index.php">
					<input type="hidden" name="operation" value="usermanager"/>
					<div id="users_control" class="toolbar">
						<input type='button' id="activate" class="button" value="{show text='Activate'}"/>
						<input type='button' id="modify" class="button" value="{show text='Modify'}"/>
						<input type='button' id="suspend" class="button" value="{show text='Suspend'}"/>
						<input type='button' id="delete" class="button" value="{show text='Delete'}"/>
						<div class="right">
							<select class="combobox" name="condition">
								<option value="username">{show text='Username'}</option>
								<option value="email">{show text='Email'}</option>
							</select>
							<input class="text_input" type="text" name="query" value="{$smarty.request.query}"/>
							<button id="query" class="button" type="submit">{show text='Find'}</button>
						</div>
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
							{section name=user loop=$users}
							<tr>
								<td>
									<input type="checkbox" name="{$users[user].id}" />
								</td>
								<td>{$users[user].id}</td>
								<td>{$users[user].username}</td>
								<td>{$users[user].email}</td>
								<td>{$users[user].register_time}</td>
								<td>{$users[user].register_ip}</td>
								<td>{$users[user].last_login_ip}</td>
								<td>{$users[user].last_login_time}</td>
								<td>{$users[user].login_count}</td>
								<td>{$users[user].status}</td>
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
			</div>
			<div id="edit_dialog" title="{show text='Edit User'}">
				<form action="{$smarty.const.ROOT}/index.php" class="form container">
					<dl>
						<dt>
							<label for="username">{show text='Username'}:</label>
						</dt>
						<dd>
							<input id="username" name="username" class="text_input" />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="email">{show text='Email'}:</label>
						</dt>
						<dd>
							<input id="email" name="email" class="text_input" />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="status">{show text='Status'}:</label>
						</dt>
						<dd>
							<select id="status" name="status">
								<option value="0">{show text='Not Activate'}</option>
								<option value="1">{show text='Logined'}</option>
								<option value="2">{show text='Suspended'}</option>
								<option value="3">{show text='Logout'}</option>
							</select>
						</dd>
					</dl>
					<input type="hidden" id="edit_type" name="type"/>
					<input type="hidden" id="user_id" name="id"/>
				</form>
			</div>
{include file="footer.tpl"}
