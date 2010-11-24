{include file="header.tpl"}
			<script type="text/javascript">
				var root = '{$smarty.const.ROOT}/index.php';
				var gid = '{$gid}';
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
					$( "#choose_user_dialog" ).dialog({
						autoOpen: false,
						show: "blind",
						hide: "explode",
						width: 'auto',
						buttons: {
							submit: function(){
								$.post(root, {
									operation: 'groupmanager',
									gid: gid,
									type: 'add_members',
									ids: getUserIDs().join(','), 
									format: 'json'
								}, function(){
									window.loaction = window.location;
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

					function getUserIDs() {
						var userIds = [];
						$('.datagrid tr.checked').each(function(){
							userIds.push($(this).children('td').eq(1).text().trim());
						});
						return userIds;
					}

					function updateUserTable(data) {
						$('#users tbody').children().remove();
						$(data.results).each(function(index){
							var row = $('#users tbody').get(0).insertRow(index);
							$(row).mouseover(function(){
								$(this).addClass('active');
							}).mouseout(function(){
								$(this).removeClass('active');
							}).click(function(event){
								if($(event.target).attr('type') != 'checkbox')
									$(this).find('input').attr('checked', !$(this).find('input').attr('checked'));
								$(this).toggleClass('checked');
							});

							var check = row.insertCell(0);
							$(check).append($(document.createElement('input')).attr('type', 'checkbox'));
							var id = row.insertCell(1);
							$(id).text(this.id);
							var username = row.insertCell(2);
							$(username).text(this.username);
							var email = row.insertCell(3);
							$(email).text(this.email);
							var status = row.insertCell(4);
							$(status).text(this.status);
						});
					}

					$('#add, #query_user').click(function(){
						$.get(root, {
							operation: 'usermanager',
							condition: $('#condition').val(),
							query: $('#user_query').val(),
							page: $('#page').val(),
							type: 'query',
							format: 'json'
						}, function(data){
							updateUserTable(data);
							$('#choose_user_dialog').dialog('open');
						}, 'json');
					});
					$('#delete').click(function(){
						if(checkIfNoUserSelected())
							return;
						$.get(root, {
							operation: 'groupmanager',
							gid: gid,
							type: 'remove_members',
							ids: getUserIDs().join(','), 
							format: 'json'
						}, function(){
							window.location = window.location;
						}, 'json');
					});
					$('#page').change(function(){
						$('#query_form').submit();
					});
				}
			</script>
			<div id="users_result">
				<form id="query_form" action="{$smarty.const.ROOT}/index.php">
					<input type="hidden" name="operation" value="groupmanager"/>
					<input type="hidden" name="gid" value="{$gid}"/>
					<div id="users_control" class="toolbar">
						<input type='button' id="add" class="button" value="{show text='Add'}"/>
						<input type='button' id="delete" class="button" value="{show text='Delete'}"/>
						<div class="right">
							<select id="condition" class="combobox" name="condition">
								<option value="username">{show text='Username'}</option>
								<option value="email">{show text='Email'}</option>
							</select>
							<input id="query" class="text_input" type="text" name="query" value="{$smarty.request.query}"/>
							<button id="query_button" class="button" type="submit">{show text='Find'}</button>
						</div>
					</div>
					<table  class='datagrid' cellspadding='1'>
						<thead>
							<tr>
								<th>
									<input type="checkbox" id="check_all_user" />
								</th>
								<th>{show text='ID'}</th>
								<th>{show text='Username'}</th>
								<th>{show text='Email'}</th>
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
			<div id="choose_user_dialog" title="{show text='Choose User'}">
				<div id="user_search" class="toolbar">
					<div class="right">
						<select class="combobox" name="condition">
							<option value="username">{show text='Username'}</option>
							<option value="email">{show text='Email'}</option>
						</select>
						<input id='user_query' class="text_input" type="text" name="query" value="{$smarty.request.query}"/>
						<button id="query_user" class="button" type="submit">{show text='Find'}</button>
					</div>
				</div>
				<table id='users' class="datagrid" cellspadding='1' width="100%">
					<thead>
						<tr>
							<th>
								<input type="checkbox" id="check_all_user" />
							</th>
							<th>{show text='ID'}</th>
							<th>{show text='Username'}</th>
							<th>{show text='Email'}</th>
							<th>{show text='Status'}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr height='5'>
							<td colspan='10'>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
{include file="footer.tpl"}
