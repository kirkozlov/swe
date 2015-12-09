<body>
	<div class="menu">
		<div class="main">
			<!-- Nav -->
			<div class="spaceHolder">
            </div>
			<nav class="nav">
				<ul class="nav-list">
					<li class="nav-item"><a href="index.php">Startseite</a></li>
					<?php
						if(isset($_SESSION['admin']) && $_SESSION['admin'] == "1")
						{
							echo '<li class="nav-item"><a href="useradmin.php">Benutzerverwaltung</a></li>';
							echo '<li class="nav-item"><a href="anzeigenverwaltungadmin.php">Anzeigenverwaltung</a></li>';
						}
						if(isset($_SESSION['login']) && $_SESSION['login'] == true)
						{
						    echo '<li class="nav-item"><a href="interrests.php">Interessenliste</a></li>';
						    echo '<li class="nav-item"><a href="meineanzeigen.php">Meine Anzeigen</a></li>';
						    echo '<li class="nav-item"><a href="additem.php">Anzeige erstellen</a></li>';
							echo '<li class="nav-item"><a href="settings.php">Einstellungen</a></li>';
							echo '<li class="nav-item"><a href="logout.php">Ausloggen</a></li>';
						}
						else 
						{
							echo '<li class="nav-item"><a href="login.php">Einloggen</a></li>';
						}
					?>
					
				</ul>
			</nav>
			<!-- /Nav -->
		</div>
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
