<div class="container mt-5 pt-5">
    <div class="alert alert-danger text-center">
        <h2 class="display-3">{{ isset($errorCode) ? $errorCode : 400 }}</h2>
        <p class="display-5">{{ isset($message) ? $message : 'Oops! Something is wrong.' }}</p>
    </div>
</div>
