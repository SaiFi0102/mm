<noscript><div class="errorbox"><h4>Please enable JavaScript to view the connection guide!</h4></div></noscript>
<div id="guide_container">
<div class="main" id="guide_os">
	<div class="main_title">How to Connect and Play on <?php print $cms->config['websitename']; ?></div>
	<div class="content" align="center">
		<h4>Choose the operating system which you will use to play WoW.</h4><br />
		<table align="center">
			<tr align="center">
				<td class="windows_os">
					<h4><a href="#Windows_OS" onclick="Goto_WindowsOS()">
					<img src="<?php print $cms->config['websiteurl']; ?>/images/icons/windows-os.png" alt="Windows" /><br />
					Windows
					</a></h4>
				</td>
				
				<td class="mac_os">
					<h4><a href="#Mac_OS" onclick="Goto_MacOS()">
					<img src="<?php print $cms->config['websiteurl']; ?>/images/icons/mac-os.png" alt="Mac OS X" /><br />
					Mac OS X
					</a></h4>
				</td>
			</tr>
		</table>
	</div>
</div>

<!-- WINDOWS -->
<div class="main" id="guide_windows" style="display:none;">
	<div class="main_title">Windows Connection Guide</div>
	<div class="content">
		<h5>Steps:</h5>
		<ol>
			<li><a href="#Windows_Step_1" onclick="Goto_Windows_Step(1)">Registering on Website</a></li>
			<li><a href="#Windows_Step_2" onclick="Goto_Windows_Step(2)">Downloading and Installing World of Warcraft</a></li>
			<li><a href="#Windows_Step_3" onclick="Goto_Windows_Step(3)">Downloading and Installing Patches</a></li>
			<li><a href="#Windows_Step_4" onclick="Goto_Windows_Step(4)">Changing Realmlist</a></li>
			<li><a href="#Windows_Step_5" onclick="Goto_Windows_Step(5)">Logging in and Playing WoW</a></li>
		</ol>
		<b>You can skip any step if you already know how to do it or if you have already done it.</b>
	</div>
</div>

<div class="main" id="guide_windows_step_1" style="display:none;">
	<div class="main_title">Step 1: Registering on Website</div>
	<div class="content" style="font-size:18px;line-height:150%;">
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step1/2.jpg" alt="" width="639" height="670" /></div>
		<div>
			The first step is to <b><a href="register.php" title="Link to Registration Page" target="_blank">Register</a></b> an account on <?php print $cms->config['websitename']; ?> online. This account will be used to login on Website and the Game.<br /><br />
			You can register by going to registration page by clicking on the "Register" link(s) above or by <b><a href="register.php" title="Link to Registration Page" target="_blank">clicking here</a></b>.<br /><br />
			Once you have registered an account you can proceed to the next step for downloading and installing World of Warcraft.
			
			<div>
				<br /><div class="right"><a href="#Windows_Step_2" onclick="Goto_Windows_Step(2)">Step 2 <img src="<?php print $cms->config['websiteurl']; ?>/images/icons/forward.png" width="32" height="18" alt="Next"></a></div>
				<a href="#Windows_OS" onclick="Goto_WindowsOS()"><img src="<?php print $cms->config['websiteurl']; ?>/images/icons/back.png" width="32" height="18" alt="Back"> Steps</a>
			</div>
		</div>
		<div class="clear" align="center"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step1/1.jpg" alt="" width="960" height="452" /></div>
	</div>
</div>

<div class="main" id="guide_windows_step_2" style="display:none;">
	<div class="main_title">Step 2: Downloading and Installing World of Warcraft</div>
	<div class="content" style="font-size:18px;line-height:150%;">
		For downloading and installing World of Warcraft we'll use Wrath of the Lich King's Installer which downloads and installs it. This installer have to be downloaded and can be downloaded by <a href="http://static.wowmortal.com/InstallWoW.exe" target="_blank">clicking on this link.</a>
		Save the file any where you would like but I recommend saving it on the desktop.<br /><br />
		
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step2/2.jpg" alt="" width="559" height="508" /></div>
		<div>Open InstallWoW which you downloaded and proceed by clicking "OK".</div>
		<div class="clear"></div>
		
		<div class="left"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step2/3.jpg" alt="" width="711" height="476" /></div>
		<div>Select the folder where you want to download and then continue. This folder will only have the installer, not the game. You will choose the game directory in the next step.</div>
		<div class="clear"></div>
		
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step2/4.jpg" alt="" width="832" height="640" /></div>
		<div>After you click "INSTALL", follow the instructions provided by the installer and then proceed to next step when installation is complete.</div>
		<div class="clear"></div>
		
		<div class="left"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step2/5.jpg" alt="" width="741" height="590" /></div>
		<div>When installation is complete close the installer and make sure that laucher does not start patching by it self. If World of Warcraft launcher opens, close it. Proceed to next step.</div>
		<div class="clear"></div>
		
		<div>
			<br /><div class="right"><a href="#Windows_Step_3" onclick="Goto_Windows_Step(3)">Step 3 <img src="<?php print $cms->config['websiteurl']; ?>/images/icons/forward.png" width="32" height="18" alt="Next"></a></div>
			<a href="#Windows_Step_1" onclick="Goto_Windows_Step(1)"><img src="<?php print $cms->config['websiteurl']; ?>/images/icons/back.png" width="32" height="18" alt="Back"> Step 1</a>
		</div>
	</div>
</div>

<div class="main" id="guide_windows_step_3" style="display:none;">
	<div class="main_title">Step 3: Downloading and Installing Patches</div>
	<div class="content" style="font-size:18px;line-height:150%;">
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step3/1.jpg" alt="" width="288" height="536" /></div>
		<div>First you need to open the directory in which WoW has been installed, you can do it in many ways. 1 way is shown in the screenshot on the right.
		<br /><br />Another way is to manually open the folder by going to "Computer" or "My Computer". You have selected this folder after you clicked "INSTALL" in the previous step.</div>
		<div class="clear"></div>
		
		Now you have to download the patches, I have packed all the patches for you guys. There are 10 files but I have packed them in a RAR archive and you would need <b><a href="http://www.rarlab.com" target="_blank">WinRar</a></b> to unpack them. <a href="http://static.wowmortal.com/Updates.rar" target="_blank"><b>To download the patches(3.2.0 -> 3.3.5a) click here.</b></a>
		<br /><br />Save the "Updates.rar" file which you are downloading to the World of Warcraft directory which you opened in the precedure above.<br />
		
		<div class="left"><br /><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step3/3.jpg" alt="" width="578" height="533" /></div>
		<div><br />Extract or Unpack the Updates.rar after it has been downloaded by clicking "Extract Here".
		<br /><br />The extraction may take some time because the file is of 1 GB. After extraction is complete open the "Updates" directory.</div>
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step3/4.jpg" alt="" width="442" height="264" /></div>
		<div class="clear"></div>
		
		
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step3/5.jpg" alt="" width="499" height="188" />
		<br /><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step3/6.jpg" alt="" width="400" height="346" /></div>
		<div>There are 10 patch files and I have renamed them in an order to be run.<br /><br />
		Firstly run <b>"(1)WoW-3.2.0.10192-to-3.2.0.10314-enUS-patch.exe"</b> then <b>"(2)WoW-3.2.0.10314-to-3.2.2.10482-enUS-patch.exe"</b> and continue till all the patches has been installed...<br /><br />
		<b>"(4)wow-3.2.2-to-3.3.0-enUS-Win-patch"</b> is a folder so you'll open the folder and install the patch by opening <b>"Blizzard Updater"</b>.
		<br /><br /><span style="color:red;"><b>IMPORTANT:</b> </span>After every patch installed the World of Warcraft launcher will start, close it! Because it will start patching by itself to 4.0.0. We need to patch uptil 3.3.5a only.
		<br /><br />When all the patches have been successfuly installed, you can continue to next step.</div>
		<div class="clear"></div>
		
		<div>
			<br /><div class="right"><a href="#Windows_Step_4" onclick="Goto_Windows_Step(4)">Step 4 <img src="<?php print $cms->config['websiteurl']; ?>/images/icons/forward.png" width="32" height="18" alt="Next"></a></div>
			<a href="#Windows_Step_2" onclick="Goto_Windows_Step(2)"><img src="<?php print $cms->config['websiteurl']; ?>/images/icons/back.png" width="32" height="18" alt="Back"> Step 2</a>
		</div>
	</div>
</div>

<div class="main" id="guide_windows_step_4" style="display:none;">
	<div class="main_title">Step 4: Changing Realmlist</div>
	<div class="content" style="font-size:18px;line-height:150%;">
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step4/1.jpg" alt="" width="504" height="168" /><br />
		<img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step4/2.jpg" alt="" width="506" height="115" /></div>
		<div>Go back to the WoW directory and open the folder <b>"Data"</b> then open the folder <b>"enUS"</b> or it also be <b>"enGB"</b>.</div>
		<div class="clear"></div>
		
		<div class="left"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step4/3.jpg" alt="" width="308" height="395" /></div>
		<div>Right click on the <b>"realmlist"</b> or <b>"realmlist.wtf"</b> file and click on <b>"Open With"</b> or just <b>"Open"</b> if you can't find <b>"Open"</b></div>
		<div class="clear"></div>
		
		<div class="right"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step4/4.jpg" alt="" width="443" height="371" /></div>
		<div>Open the <b>"realmlist"</b> file using notepad.</div>
		<div class="clear"></div>
		
		<div class="left"><img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step4/5.jpg" alt="" width="325" height="112" /></div>
		<div>Replace the whole first line of the realmlist file with our server's realmserver hostname. That means replace the first line with "<b><code>set realmlist <?php print $LOGON_REALMLIST; ?></code></b>" then "Save" the file.<br /><br />
		Once you have changed the realmlist you can now login game. I also created a step for that so check it out.</div>
		<div class="clear"></div>
		
		<div>
			<br /><div class="right"><a href="#Windows_Step_5" onclick="Goto_Windows_Step(5)">Step 5 <img src="<?php print $cms->config['websiteurl']; ?>/images/icons/forward.png" width="32" height="18" alt="Next"></a></div>
			<a href="#Windows_Step_3" onclick="Goto_Windows_Step(3)"><img src="<?php print $cms->config['websiteurl']; ?>/images/icons/back.png" width="32" height="18" alt="Back"> Step 3</a>
		</div>
	</div>
</div>

<div class="main" id="guide_windows_step_5" style="display:none;">
	<div class="main_title">Step 5: Logging in and Playing WoW</div>
	<div class="content" align="center" style="font-size:18px;line-height:150%;">
		<img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step5/1.jpg" alt="" width="532" height="489" /><br /><br />
		<img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step5/2.jpg" alt="" width="590" height="568" /><br /><br />
		<img src="<?php print $cms->config['websiteurl']; ?>/images/guide/windows/step5/3.jpg" alt="" width="445" height="441" /><br /><br />
		That's it! You've done it. If you have any questions or any problems, please contact us using the "Contact Us" link on the Links Bar. See you in the game :)<br />
		Good Luck and Have fun!
		<div align="left">
			<a href="#Windows_Step_4" onclick="Goto_Windows_Step(4)"><img src="<?php print $cms->config['websiteurl']; ?>/images/icons/back.png" width="32" height="18" alt="Back"> Step 4</a>
		</div>
	</div>
</div>

<!-- MAC OS X -->
<div class="main" id="guide_mac" style="display:none;">
	<div class="main_title">Mac OS Connection Guide</div>
	<div class="content" align="center">
		<h4>Currently Mac OS Guide is not availible. Only Windows guide is availible. Sorry for inconveniences.</h4>
	</div>
</div>

</div>
<script type="text/javascript">
function Goto_WindowsOS()
{
	$("#guide_container .main:visible").hide();
	$("#guide_windows").fadeIn(500);
}
function Goto_MacOS()
{
	$("#guide_container .main:visible").hide();
	$("#guide_mac").fadeIn(500);
}
function Goto_Windows_Step(step)
{
	$("#guide_container .main:visible").hide();
	$("#guide_windows_step_" + step).fadeIn(500);
}

LocationHref = window.location.href;
HrefHash = LocationHref.match(/#(.+)/i);
if(HrefHash[1] == "Windows_OS")
{
	Goto_WindowsOS();
}
if(HrefHash[1] == "Mac_OS")
{
	Goto_MacOS();
}
if(HashMatch = HrefHash[1].match(/Windows_Step_(.+)/i))
{
	Goto_Windows_Step(parseInt(HashMatch[1]));
}
</script>