<?php

require __DIR__ . "/middleware/AuthMiddleware.php";
AuthMiddleware::check();

require __DIR__ . "/controller/EventController.php";

if ($_SESSION['user']['role'] !== 'ADMIN') {
    header('Location: /webinar-app/beranda.php');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
}

if (isset($_POST['support'])) {
    header('Location: support.php');
}

if (isset($_POST['home'])) {
    header('Location: /webinar-app/beranda.php');
}

if (isset($_POST['my_ticket'])) {
    header('Location: myticket.php');
}

if (isset($_POST['ticket'])) {
    header('Location: ticket.php');
}

if (isset($_POST['event_register'])) {
    header('Location: event_register.php');
}

$eventController = new EventController();

/**
 * Get event by id
 * return information of event
 */
if (isset($_GET['id'])) {
    $event = $eventController->getById($_GET['id']);
}

/**
 * Change status
 * Update status to published
 */
if (isset($_POST['change_status'])) {

    $result = $eventController->putPublish($_GET['id']);

    if ($result['success']) {

        echo "<script>alert('Support ticket updated successfully');</script>";

        header("Refresh: 3; url=" . $_SERVER['REQUEST_URI']);
    } else {

        echo "<script>alert('Failed to update support ticket');</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Ticket</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=menu" />


</head>

<body>
    <!-- NAVBAR | CAN IMPROVE WITH COMPONENT -->
    <nav class="navbar bg-body-tertiary sticky-sm-top">
        <div class="container-fluid">
            <div class="">

                <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <span class="material-symbols-outlined">
                        menu
                    </span>
                </button>

                <img src="./images/logo_if.png" alt="Logo" width="40" height="24" class="d-inline-block align-text-top">

                <span class="h6 mx-2">Ticket Detail</span>

            </div>
        </div>
    </nav>

    <div class="container my-3">

        <div class="row justify-content-center">

            <h1 class="text-center my-5">Event Detail</h1>

            <div class="col-12 col-lg-6">

                <div class="card mx-auto">
                    <div class="card-body">
                        <!-- SUBJECT -->
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 fw-semibold"><?= $event["data"]["title"] ?></span>
                        </div>
                        <!-- TYPE -->
                        <p class="card-text">
                            <?php if ($event["data"]["attendance_type"] === "OFFLINE") : ?>
                                <span class="badge bg-secondary">Offline</span>
                            <?php else : ?>
                                <span class="badge bg-secondary">Online</span>
                            <?php endif; ?>
                        </p>
                        <!-- DESCRIPTION -->
                        <p class="card-text"><?= $event["data"]["description"] ?></p>
                    </div>
                </div>

                <?php if (!$event["data"]["published"]) : ?>
                    <!-- CHANGE STATUS -->
                    <div class="card mx-auto mt-3">

                        <div class="card-body">

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Change Status
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <form method="post">

                                                <div class="mb-3">

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="published" id="published" value="TRUE" required>
                                                        <label class="form-check-label" for="published">
                                                            Published
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="d-flex flex-row-reverse">
                                                    <button type="submit" name="change_status" class="btn btn-primary">Change Status</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php else: ?>
                    <div class="card mx-auto mt-3">
                        <div class="card-body">
                            <span class="badge bg-success text-light">Webinar has been published</span>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>


    <!-- NAVIGATION | CAN IMPROVE WITH COMPONENT -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" id="offcanvasExampleLabel">Webinar UKDC</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ms-3">
            <form method="post">
                <div class="mb-3">
                    <button type="submit" name="home" class="nav-link">
                        <h5>
                            Home
                        </h5>
                    </button>
                </div>

                <div class="mb-3">
                    <button type="submit" name="support" class="nav-link">
                        <h5>
                            Support
                        </h5>
                    </button>
                </div>

                <div class="mb-3">
                    <button type="submit" name="my_ticket" class="nav-link">
                        <h5>
                            My Ticket
                        </h5>
                    </button>
                </div>

                <div class="mb-3">
                    <?php if ($_SESSION['user']['role'] === 'ADMIN') : ?>
                        <button type="submit" name="ticket" class="nav-link">
                            <h5>
                                Ticket
                            </h5>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <?php if ($_SESSION['user']['role'] === 'ADMIN') : ?>
                        <button type="submit" name="event_register" class="nav-link">
                            <h5>
                                Event Register
                            </h5>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <button type="submit" name="logout" class="nav-link">
                        <h5>
                            Logout
                        </h5>
                    </button>
                </div>


            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>