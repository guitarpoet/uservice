{include file="header.tpl"}
			<script type="text/javascript">
				initialize = function($) {
					$('.datagrid tbody tr input').each(function(){
						if($(this).attr('checked')){
							$(this).parents('tr').addClass('checked');
						}
					});
					$('.button').button();
					$('.datagrid tbody tr').mouseover(function(){
						$(this).addClass('active');
					}).mouseout(function(){
						$(this).removeClass('active');
					}).click(function(){
						$(this).find('input').attr('checked', !$(this).find('input').attr('checked'));
						$(this).toggleClass('checked');
					});
					$('#check_all_user').click(function(){
						$('.datagrid tbody tr').click();
					});
				}
			</script>
			<div id="users_result">
				<div id="users_control" class="toolbar">
					<button id="add" class="button">{show text='Add'}</button>
					<button id="modify" class="button">{show text='Modify'}</button>
					<button id="delete" class="button">{show text='Delete'}</button>
					<div class="right">
						<select class="combobox" name="condition">
							<option value="username">{show text='Username'}</option>
							<option value="email">{show text='Email'}</option>
						</select>
						<input class="text_input" type="text" name="condition"/>
						<button id="query" class="button">{show text='Find'}</button>
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
						<tr height='5'></tr>
					</tfoot>
				</table>
			</div>
{include file="footer.tpl"}
