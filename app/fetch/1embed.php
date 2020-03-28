<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');



$zurl = 'https://www.malaymail.com/news/life/2020/03/25/covid-19-tesco-malaysia-rescues-clueless-husbands-with-illustrated-guide-to/1850117';

// echo __DIR__.''; 
// exit;
include '../vendor/embed/embed/src/autoloader.php';

// Use default config as template
$fetch_meta_opts = \Embed\Embed::$default_config;
// Do some config modifications
$fetch_meta_opts['min_image_width'] = 60;
$fetch_meta_opts['min_image_height'] = 60;
$fetch_meta_opts['html']['max_images'] = 10;
$fetch_meta_opts['html']['external_images'] = false;

 include 'functions/functions-embed.php';


$providerData = [
    'title' => 'printText',
    'description' => 'printText',
    'url' => 'printUrl',
    'type' => 'printText',
    'tags' => 'printArray',
    'imagesUrls' => 'printArray',
    'code' => 'printCode',
    'feeds' => 'printArray',
    'width' => 'printText',
    'height' => 'printText',
    'authorName' => 'printText',
    'authorUrl' => 'printUrl',
    'providerIconsUrls' => 'printArray',
    'providerName' => 'printText',
    'providerUrl' => 'printUrl',
    'publishedTime' => 'printText',
    'license' => 'printUrl',
];

$adapterData = [
    'title' => 'printText',
    'description' => 'printText',
    'url' => 'printUrl',
    'type' => 'printText',
    'tags' => 'printArray',
    'image' => 'printImage',
    'imageWidth' => 'printText',
    'imageHeight' => 'printText',
    'images' => 'printArray',
    'code' => 'printCode',
    'feeds' => 'printArray',
    'width' => 'printText',
    'height' => 'printText',
    'aspectRatio' => 'printText',
    'authorName' => 'printText',
    'authorUrl' => 'printUrl',
    'providerIcon' => 'printImage',
    'providerIcons' => 'printArray',
    'providerName' => 'printText',
    'providerUrl' => 'printUrl',
    'publishedTime' => 'printText',
    'license' => 'printUrl',
];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Embed tests</title>

    <style type="text/css">
        body {
            font-family: Helvetica, Arial, sans-serif;
            min-width: 650px;
            margin: 0;
            padding: 0;
        }

        a {
            color: inherit;
            font-size: 0.9em;
        }

        a:hover {
            text-decoration: none;
        }

        img {
            display: block;
            margin-bottom: 0.5em;
        }

        pre {
            overflow: auto;
            background: #EEE;
            padding: 1em;
        }

        /* form */
        form {
            background: #EEE;
            border-bottom: solid 1px #DDD;
            color: #666;
            padding: 3em 1.5em;
        }

        fieldset {
            border: none;
            padding: 0;
        }

        label {
            display: block;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="url"] {
            border: none;
            background: white;
            border-radius: 2px;
            box-sizing: border-box;
            width: 100%;
            margin: 5px 0;
            font-size: 1.3em;
            padding: 0.5em;
            color: #666;
        }

        button {
            font-size: 1.6rem;
            font-weight: bold;
            font-family: Arial;
            background: yellowgreen;
            border: none;
            border-radius: 2px;
            padding: 0.2em 1em;
            cursor: pointer;
            margin-top: 5px;
        }

        button:hover {
            background: black;
            color: white;
        }

        /* result */
        section {
            padding: 1.5em;
        }

        section h1,
        section h2 {
            font-size: 2em;
            color: #666;
            letter-spacing: -0.02em;
        }

        section h2 {
            margin-top: 3em;
        }

        table {
            text-align: left;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            vertical-align: top;
            padding: 0.5em 1em 0.5em 0;
            border-top: solid 1px #DDD;
        }

        th {
            width: 200px;
        }

        #advanced-data {
            display: none;
        }

        .view-advanced-data {
            margin: 2em 0;
            text-align: center;
        }
    </style>
</head>

<?php if ($zurl) : ?>
    <section>
        <h1>Result:</h1>

        <?php
        echo $zurl;
        try {
            $dispatcher = new Embed\Http\CurlDispatcher();
            $info = Embed\Embed::create($zurl, $fetch_meta_opts, $dispatcher);
        } catch (Exception $exception) {
            echo '<table>';
            foreach ($dispatcher->getAllResponses() as $response) {
                echo '<tr>';
                echo '<th>' . $response->getUrl() . '</th>';
                echo '</tr><tr><td>';
                printHeaders($response->getHeaders());
                echo '</td><tr><td><pre>';
                printArray($response->getInfo());
                echo '</td><tr><td><pre>';
                printText($response->getContent());
                echo '</pre></td></tr>';
            }
            echo '</table>';

            throw $exception;
        }
        ?>
        <h2>
            T1
        </h2>
        <table id="t1">
            <?php foreach ($adapterData as $name => $fn) : ?>
                <tr>
                    <th><?php echo $name; ?></th>
                    <td><?php $fn($info->$name); ?></td>
                </tr>
            <?php endforeach ?>
        </table>
        <h2>
          
        </h2>
        <div class="view-advanced-data">
            <button onclick="document.getElementById('advanced-data').style.display = 'block'; this.style.display = 'none';">View all collected data</button>
        </div>
 
        <div id="advanced-data">
            <?php foreach ($info->getProviders() as $providerName => $provider) : ?>
                <h2><?php echo $providerName; ?> <small>provider</small></h2>

                <?php if (empty($provider->getBag()->getAll())) : ?>
                    <p>No data collected</p>
                    <?php continue; ?>
                <?php endif ?>

                <table>
                    <?php foreach ($providerData as $name => $fn) : ?>
                        <?php if (!empty($provider->{'get' . $name}())) : ?>
                            <tr>
                                <th><?php echo $providerName . '.' . $name; ?></th>
                                <td><?php $fn($provider->{'get' . $name}(), false); ?></td>
                            </tr>
                        <?php endif ?>
                    <?php endforeach ?>

                    <tr>
                        <th>&rarr; All data collected</th>
                        <td><?php printArray($provider->getBag()->getAll()); ?></td>
                    </tr>
                </table>
            <?php endforeach ?>

            <h2>Http requests</h2>

            <table>
                <?php foreach ($info->getDispatcher()->getAllResponses() as $response) : ?>
                    <tr>
                        <th>
                            <?php if ((string) $response->getStartingUrl() !== (string) $response->getUrl()) : ?>
                                <?= $response->getStartingUrl() ?> <code>=&gt;</code>
                            <?php endif ?>

                            <?= $response->getUrl() ?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?php printHeaders($response->getHeaders()); ?>
                            <?php printArray($response->getInfo()); ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>

            <h2>Content</h2>

            <pre>
                    <?php printText($info->getResponse()->getContent()); ?>
                </pre>
        </div>
    </section>

<?php endif; ?>
</body>

</html>