<body>




	<div class="menu">

		<div class="nav-wrapper">

			<nav>
			<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul id="nav-mobile" class="left hide-on-med-and-down">
					<li class=""><a href="index.php">Startseite</a></li>
					<?php
						if(isset($_SESSION['admin']) && $_SESSION['admin'] == "1")
						{
							echo '<li><a href="useradmin.php">Benutzerverwaltung</a></li>';
							echo '<li><a href="anzeigenverwaltungadmin.php">Anzeigenverwaltung</a></li>';
                            echo '</ul>';
                            echo '<ul class="right">';
							echo '<li><a href="logout.php">Ausloggen </a></li>';
							echo ' </ul>';
                            echo '<ul class="side-nav" id="mobile-demo">';
                            echo '<li><a href="useradmin.php">Benutzerverwaltung</a></li>';
							echo '<li><a href="anzeigenverwaltungadmin.php">Anzeigenverwaltung</a></li>';
                            echo '</ul>';
						}
						else if(isset($_SESSION['login']) && $_SESSION['login'] == true)
						{     
						    echo '<li><a href="interrests.php">Interessenliste</a></li>';
						    echo '<li><a href="meineanzeigen.php">Meine Anzeigen</a></li>';
						    echo '<li><a href="additem.php">Anzeige erstellen</a></li>';
							echo '<li><a href="settings.php">Einstellungen</a></li>';
							echo '</ul>';
							echo '<ul class="right">';
							echo '<li><a href="logout.php">Ausloggen </a></li>';
							echo ' </ul>';
							echo '<ul class="side-nav" id="mobile-demo">';
                            echo '<li><a href="index.php">Startseite</a>';
						    echo '<li><a href="interrests.php">Interessenliste</a></li>';
						    echo '<li><a href="meineanzeigen.php">Meine Anzeigen</a></li>';
						    echo '<li><a href="additem.php">Anzeige erstellen</a></li>';
							echo '<li><a href="settings.php">Einstellungen</a></li>';
							echo '</ul>';
							echo '</ul>';
						}
						else 
						{
                            echo '</ul>';
                            echo '<ul class="side-nav" id="mobile-demo">';
                            echo '<li><a href="index.php">Startseite</a>';
							echo '</ul>';
							echo '<ul class="right">';
							echo '<li><a class="right" href="login.php">Einloggen</a></li>';
							echo' </ul>';
						}
					?>
					
				</ul>
			</nav>
			<!-- /Nav -->
	</div>
</body>
<script language="javascript" type="text/javascript">
    (function () {

		    // Create mobile element
		    var mobile = document.createElement('div');
		    mobile.className = 'nav-mobile';
		    document.querySelector('.nav').appendChild(mobile);

		    // hasClass
		    function hasClass(elem, className) {
		        return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
		    }

		    // toggleClass
		    function toggleClass(elem, className) {
		        var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
		        if (hasClass(elem, className)) {
		            while (newClass.indexOf(' ' + className + ' ') >= 0) {
		                newClass = newClass.replace(' ' + className + ' ', ' ');
		            }
		            elem.className = newClass.replace(/^\s+|\s+$/g, '');
		        } else {
		            elem.className += ' ' + className;
		        }
		    }

		    // Mobile nav function
		    var mobileNav = document.querySelector('.nav-mobile');
		    var toggle = document.querySelector('.nav-list');
		    mobileNav.onclick = function () {
		        toggleClass(this, 'nav-mobile-open');
		        toggleClass(toggle, 'nav-active');
		    };
		})();
</script>
