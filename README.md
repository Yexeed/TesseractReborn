# TesseractReborn
Tesseract server software for Minecraft: PE\W10 1.1
# Introduction
So it's a basic tesseract but for 1.1, and it has some fixes by me.
You can use it if you want.
# Notes
* Use PHP7.2, i made some fixes for it. ([Linux](https://jenkins.pmmp.io/job/PHP-7.2-Linux-x86_64/ "PHP7.2 binaries for linux") OR [Windows](https://ci.appveyor.com/api/buildjobs/6m40vvy7nttas1rc/artifacts/php-7.2.5-vc15-x64.zip "PHP7.2 binaries for Windows"))
* It has a same structure as in PMMP in some places, example:
  * CommandExecutor::onCommand()
  * Task::onRun()
  * Packets namespace (\pocketmine\network\mcpe\protocol)
