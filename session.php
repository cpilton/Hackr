<!doctype html>
<html>
    <head>
 <title>Hackr</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="styles/form.css" type="text/css">

<link rel="icon" href="images/favicon.ico" type="image/x-icon">
   
   <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#username").keyup(function (e) {
	
		
		$(this).val($(this).val().replace(/\s/g, ''));
		
		var username = $(this).val();
		if(username.length < 4){$("#user-result").html('');return;}
		
		if(username.length >= 4){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('php/check_username.php', {'username':username}, function(data) {
			  $("#user-result").html(data);
			});
		}
	});	
});
</script>
   
    </head>
    <body>
    
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
 <banner>
		<header>
			</br></br>
			<heading>
				HACKR
			</heading>
			</br>
			<heading2>
			<?php if ($_GET['a'] == 'login') { ?>
				LOGIN
				<?php } else { ?>
				CREATE ACCOUNT
				<?php } ?>
			</heading2>
			</br>
			</br>
		</header>
	</banner>
            
            <container class="content">
				
        <?php if ($_GET['a'] == 'login') : ?>
         

            <?php if (isset($_GET['m']) && $_GET['m'] == 'reg_succ') : ?>
                <div class="success">
                    <p>You have been successfully registered. Please login below.</p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['m']) && $_GET['m'] == 'invalid') : ?>
                <div class="error">
                    <p>The username and password you entered didn't match.</p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" id="session" style="width:375px;">
           
                <fieldset>
					</br>
			<heading3>Login</heading3>
                    <label for="empnumber">Username:</label>
                   
                    <input type="text" name="username" id="username" required>
                
                    <label for="password">Password</label>
                  
                    <input type="password" name="password" id="password" required>
                
<input type="submit" value="Login" id="submit">
                </fieldset>
                    
                
               
            </form>
           
        <?php endif; ?>
        
        <?php if ($_GET['a'] == 'register') : ?>
	
            <form action="register.php" method="post">
                <table>
                <tr><td>
                <fieldset>
					</br>
					<span class="number">1</span><heading3>Personal Information</heading3> 
              
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required> 
                
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required>
                    
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" required>
                     
                
                </fieldset>
                </td>
                <td>
                 <fieldset>
					 </br><span class="number">2</span>
					<heading3>Account Details</heading3> 
                   
                    <label for="username">Username<span id="user-result"></label>
                    
                    <input type="text" name="username" id="username" required>
                    
                    <label for="password" >Password</label>
                    <input type="password" name="password" id="password" pattern=".{8,}" title="8 characters minimum" required>
               
                   
               
                  
                   
</select>
              <input type="submit" value="Create Account" id="submit">
               </fieldset>
                  </td>
</tr>
</table>  
                    

                
            </form>
      
      
         
        <?php endif; ?>

        </container>

</br>
</br>
		<table width="50%" style="inline-display:block; margin:auto;">
			<tr>
				<td style="text-align: left"><a href="<?php print $_SESSION['current_page']?>"><heading_button>Go Back</heading_button></a>
				</td>
				<?php if ($_GET['a'] == 'login') { ?>
				<td style="text-align: right"><a href="session.php?a=register" style="text-align: right"><heading_button>Register</heading_button></a>
				</td>
				<?php } else { ?>
				<td style="text-align: right"><a href="session.php?a=login" style="text-align: right"><heading_button>Login</heading_button></a>
				</td>
				<?php } ?>
				
			</tr>
		</table>
	<footer>
		Callum Pilton © 2017-2018
	</footer>
    </body>
</html>
<!-- Callum Pilton -->