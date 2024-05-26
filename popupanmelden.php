<!-- Einbinden mit < ?php include 'popupanmelden.php' ?> -->

<div id="popupanmelden" class="popupanmelden" style="<?php echo isset($_SESSION['registration_success']) ? 'display:block;' : 'display:none;'; ?>">
    <form action="signin.php" class="form-container" method="post">
        <h1>Anmeldung</h1>
        <h3><?php if (isset($_SESSION['registration_success'])): ?>
            <p style="color:green;"><?php echo $_SESSION['registration_success']; unset($_SESSION['registration_success']); ?></p>
        <?php endif; ?></h3>
        <label for="email"></label>
        <input type="text" placeholder="Email" name="email" required>
        <label for="psw"></label>
        <input type="password" placeholder="Passwort" name="psw" required>
        <button type="submit" class="btn">Login</button>
        <button type="button" class="btn cancel" onclick="closePopup()">Abbrechen</button>
    </form>
</div>

