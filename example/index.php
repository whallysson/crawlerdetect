<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
        <title>Crawler Detect</title>
    </head>
    <body>
        <?php
        // com composer
        require __DIR__ . '/../vendor/autoload.php';

        // sem composer
        require __DIR__ . '/../src/Fixtures/AbstractProvider.php';
        require __DIR__ . '/../src/Fixtures/Crawlers.php';
        require __DIR__ . '/../src/Fixtures/Exclusions.php';
        require __DIR__ . '/../src/Fixtures/Headers.php';
        require __DIR__ . '/../src/CrawlerDetect.php';



        use CodeBlog\CrawlerDetect\CrawlerDetect;

        $Crawler = new CrawlerDetect();

        var_dump($Crawler->isCrawler());
        ?>
    </body>
</html>
