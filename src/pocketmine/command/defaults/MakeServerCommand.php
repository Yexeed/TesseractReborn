<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\utils\TextFormat;

class MakeServerCommand extends VanillaCommand {

	/**
	 * MakeServerCommand constructor.
	 *
	 * @param string $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%tesseract.command.makeserver.description",
			"%tesseract.command.makeserver.usage"
		);
		$this->setPermission("tesseract.command.makeserver");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}

        if(strpos(\pocketmine\PATH, "phar://") === 0){
            $sender->sendMessage(TextFormat::RED . "This command can only be used on a server running from source code");
            return true;
        }
        $server = $sender->getServer();
        $pharPath = $server->getPluginPath() . DIRECTORY_SEPARATOR . "Tesseract" . DIRECTORY_SEPARATOR . $server->getName() . "_" . $server->getPocketMineVersion() . ".phar";
        $metadata = [
            "name" => $server->getName(),
            "version" => $server->getPocketMineVersion(),
            "api" => $server->getApiVersion(),
            "minecraft" => $server->getVersion(),
            "creationDate" => time(),
            "protocol" => ProtocolInfo::CURRENT_PROTOCOL
        ];
        $stub = '<?php require_once("phar://". __FILE__ ."/src/pocketmine/PocketMine.php");  __HALT_COMPILER();';
        $filePath = realpath(\pocketmine\PATH) . DIRECTORY_SEPARATOR;
        $filePath = rtrim($filePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->buildPhar($sender, $pharPath, $filePath, ['src'], $metadata, $stub, \Phar::SHA1);
        $sender->sendMessage($server->getName() . " " . $server->getPocketMineVersion() . " Phar file has been created on " . $pharPath);
        return true;
	}
    private function preg_quote_array(array $strings, string $delim = null) : array{
        return array_map(function(string $str) use ($delim) : string{ return preg_quote($str, $delim); }, $strings);
    }
    private function buildPhar(CommandSender $sender, string $pharPath, string $basePath, array $includedPaths, array $metadata, string $stub, int $signatureAlgo = \Phar::SHA1){
        if(file_exists($pharPath)){
            $sender->sendMessage("Phar file already exists, overwriting...");
            try{
                \Phar::unlinkArchive($pharPath);
            }catch(\PharException $e){
                //unlinkArchive() doesn't like dodgy phars
                unlink($pharPath);
            }
        }
        $sender->sendMessage("[Tesseract] Adding files...");
        $start = microtime(true);
        $phar = new \Phar($pharPath);
        $phar->setMetadata($metadata);
        $phar->setStub($stub);
        $phar->setSignatureAlgorithm($signatureAlgo);
        $phar->startBuffering();
        //If paths contain any of these, they will be excluded
        $excludedSubstrings = [
            DIRECTORY_SEPARATOR . ".", //"Hidden" files, git information etc
            realpath($pharPath) //don't add the phar to itself
        ];
        $regex = sprintf('/^(?!.*(%s))^%s(%s).*/i',
            implode('|', $this->preg_quote_array($excludedSubstrings, '/')), //String may not contain any of these substrings
            preg_quote($basePath, '/'), //String must start with this path...
            implode('|', $this->preg_quote_array($includedPaths, '/')) //... and must be followed by one of these relative paths, if any were specified. If none, this will produce a null capturing group which will allow anything.
        );
        $directory = new \RecursiveDirectoryIterator($basePath, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::CURRENT_AS_PATHNAME); //can't use fileinfo because of symlinks
        $iterator = new \RecursiveIteratorIterator($directory);
        $regexIterator = new \RegexIterator($iterator, $regex);
        $count = count($phar->buildFromIterator($regexIterator, $basePath));
        $sender->sendMessage("[Tesseract] Added $count files");
        $sender->sendMessage("[Tesseract] Checking for compressible files...");
        foreach($phar as $file => $finfo){
            /** @var \PharFileInfo $finfo */
            if($finfo->getSize() > (1024 * 512)){
                $sender->sendMessage("[Tesseract] Compressing " . $finfo->getFilename());
                $finfo->compress(\Phar::GZ);
            }
        }
        $phar->stopBuffering();
        $sender->sendMessage("[Tesseract] Done in " . round(microtime(true) - $start, 3) . "s");
    }
}