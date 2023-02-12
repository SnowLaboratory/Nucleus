<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            background: rgb(20, 20, 20);
            color: white;
            font-family: 'Source Code Pro', monospace;
            padding: 0;
            margin: 0;
        }

        body *,
        body * * {
            font-weight: normal;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .trace {
            color: rgb(250, 250, 250);
            display: inline-block;
            text-decoration: none;
        }

        .trace-wrapper {
            padding: 0.75rem;
        }

        .trace-wrapper:hover {
            background-color: rgb(50, 50, 50);
            cursor: pointer;
            border-radius: 0.25rem;
        }

        /* ol.center-list {
            display: table;
            margin-left: auto;
            margin-right: auto;
            list-style-position: inside;
        } */

        ol li:not(li:first-of-type),
        ol>div {
            margin-top: calc(0.75rem / 2);
            padding-top: calc(0.75rem / 2);
            border-top: 1px solid gray;
        }
    </style>
</head>

<body>


    <div style="background: rgb(240, 60, 60); padding: 1rem;">
        <div class="container">
            <h3 class="margin-bottom: 2rem;">
                <?= $error->getMessage(); ?>
            </h3>

            <a class="trace" href="vscode://file/<?= $error->getFile() ?>:<?= $error->getLine() ?>">
                <span style="display: flex; font-size: 0.8rem; color: white; opacity: 0.8">
                    <?= $error->getFile() ?>
                    <span>:</span>
                    <?= $error->getLine() ?>
                </span>
            </a>
        </div>
    </div>

    <div class="container">
        <ol reversed class="center-list">
            <?php foreach ($error->getTrace() as $trace) : ?>
                <?php if (isset($trace['file'])) : ?>
                    <li>
                        <div class="trace-wrapper">
                            <a class="trace" href="vscode://file/<?= data_get($trace, 'file') ?>:<?= data_get($trace, 'line') ?>">
                                <span style="display: flex;">
                                    <span style="font-weight: bold;"><?= data_get($trace, 'class') ?></span>
                                    <span style="opacity: 0.8;"><?= data_get($trace, 'type') ? '::' : '' ?></span>
                                    <span style="font-weight: bold;"><?= data_get($trace, 'function') ?></span>
                                    <?php if (isset($trace['args'])) : ?>
                            
                                        <?php $output = [];
                                        foreach (data_get($trace, 'args') as $arg) :
                                            $output[] = is_object($arg) ? get_class($arg) : $arg;
                                        endforeach 
                                        ?>

                                        <span style="color: #908ea3">(<?= implode(', ', $output) ?>)</span>
                                    <?php endif ?>
                                </span>
                                <span style="display: flex; font-size: 0.8rem; color: rgb(200, 200, 200)">
                                    <?= data_get($trace, 'file') ?>
                                    <span>:</span>
                                    <?= data_get($trace, 'line') ?>
                                </span>
                            </a>
                        </div>

                    </li>
                <?php else : ?>
                    <div style="text-align: center; padding: 0.75rem;">
                        ...
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </ol>

    </div>


</body>

</html>