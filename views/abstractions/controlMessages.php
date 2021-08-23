<?php 

if(isset($_GET['modify'])) {
    switch ($_GET['modify']) {
        case 1:
            echo '<p class="correctText">'.ucfirst($_GET['entity']).' successfully modified</p>';
            break;
        case 0:
            echo '<p class="errorText">'.ucfirst($_GET['entity']).' was not modified due to error</p>';
            break;
        case 2:
            echo '<p class="errorText">Identificators must be unique</p>';
            break;
    }
    header("Refresh:10; url=index.php");
}

if(isset($_GET['invalidPerms'])) {
    echo '<p class="errorText">You don\'t have enought permissions</p>';
    header("Refresh:10; url=index.php");
}

if(isset($_GET['loggedstatus'])) {
    echo '<p class="errorText">Currently logged</p>';
    header("Refresh:10; url=index.php");
}
// FROM VIEWS

if(isset($_GET['badUrl'])) {
    echo '<p class="errorText">Use a correct link</p>';
    header("Refresh:10; url=index.php");
}

if(isset($_GET['notFound'])) {
    if(isset($_GET['entity'])) {
        switch ($_GET['entity']) {
            case 'user':
                echo '<p class="errorText">User not found</p>';
                header("Refresh:10; url=index.php");
                break;
            
            case 'product':
                echo '<p class="errorText">Product not found</p>';
                header("Refresh:10; url=index.php");
                break;
        }
    } else {
        echo '<p class="errorText">Entity not found</p>';
        header("Refresh:10; url=index.php");
    }
}

if(isset($_GET['fillTheFields'])) {
    echo '<p class="errorText">Fill all the form fields</p>';
    header("Refresh:10; url=index.php");
}

if(isset($_GET['add'])) {
    switch ($_GET['add']) {
        case 1:
            echo '<p class="correctText">'.ucfirst($_GET['entity']).' successfully created</p>';
            break;
        case 0:
            echo '<p class="errorText">'.ucfirst($_GET['entity']).' was not created due to error</p>';
            break;
        case 2:
            echo '<p class="errorText">Identificators must be unique</p>';
            break;
    }
    header("Refresh:10; url=index.php");
}

if(isset($_GET['delete'])) {
    switch ($_GET['delete']) {
        case 1:
            echo '<p class="correctText">'.ucfirst($_GET['entity']).' successfully deleted</p>';
            break;
        case 0:
            echo '<p class="errorText">'.ucfirst($_GET['entity']).' was not deleted due to error</p>';
            break;
    }
    header("Refresh:10; url=index.php");
}