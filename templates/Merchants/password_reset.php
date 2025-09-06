
<div class="pt-4 pb-2">
    <h5 class="card-title text-center pb-0 fs-4"><?= __ ('Password reset') ?></h5>
    <p class="text-center small"><?= __ ('Enter your email and we will send you a reset email.') ?></p>
</div>

<form class="row g-3 needs-validation" novalidate action="/merchants/check-email" method="post">
	 
    <div class="col-12">
        <label for="yourUsername" class="form-label">Username</label>
        <div class="input-group has-validation">
            <span class="input-group-text" id="inputGroupPrepend">@</span>
            <input type="text" name="email" class="form-control" id="email" required>
            <div class="invalid-feedback"><?= __ ('Please enter your email') ?></div>
        </div>
    </div>
	 
    <div class="col-12">
        <button class="btn btn-primary w-100" type="submit"><?= __ ('Go') ?></button>
    </div>
</form>
