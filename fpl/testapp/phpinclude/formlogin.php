<ul class="dropdown-menu dropdown-lr animated slideInRight" role="menu">
	<?php if(FPLGlobal::get_user_identity()->is_authenticated) { ?>
		<div class="col-lg-12">
    		<div class="text-center">
    			<h3>
    				<b><a href="usermgt-user-logout.do">D&eacute;connexion</a></b>
    			</h3>
    		</div>
		</div>
	<?php } else { ?>
	<div class="col-lg-12">
		<div class="text-center">
			<h3>
				<b>Connexion</b>
			</h3>
		</div>
		<form id="ajax-login-form" action="http://phpoll.com/login/process"
			method="post" role="form" autocomplete="off">
			<div class="form-group">
				<label for="username">Identifiant</label> <input type="text"
					name="username" id="username" tabindex="1" class="form-control"
					placeholder="Identifiant" value="" autocomplete="off">
			</div>

			<div class="form-group">
				<label for="password">Password</label> <input type="password"
					name="password" id="password" tabindex="2" class="form-control"
					placeholder="Mot de passe" autocomplete="off">
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-xs-7">
						<input type="checkbox" tabindex="3" name="remember" id="remember">
						<label for="remember"> Se rappeler de moi</label>
					</div>
					<div class="col-xs-5 pull-right">
						<input type="submit" name="login-submit" id="login-submit"
							tabindex="4" class="form-control btn btn-success"
							value="Connexion">
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-lg-12">
						<div class="text-center">
							<a href="http://phpoll.com/recover" tabindex="5"
								class="forgot-password">Mot de passe oubli&eacute;&nbsp;?</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php } ?>
</ul>