<!-- Einbinden hinter dem Button mit < ?php include 'popupregistrieren.php' ?> -->

<div id="popupregistrieren" class="popupregistrieren">
    <form action="signup.php" class="form-container" method="post">
        <h1>Registrierung</h1>
        <label for="forname"></label>
        <input type="text" placeholder="Vorname angeben" name="forname" required>
        <label for="surname"></label>
        <input type="text" placeholder="Nachname angeben" name="surname" required>
        <label for="email"></label>
        <input type="text" placeholder="E-Mail eingeben" name="email" required>
        <label for="psw"></label>
        <input type="password" placeholder="Passwort eingeben" name="psw" required>
        <label for="confirm_psw"></label>
        <input type="password" placeholder="Passwort wiederholen" name="confirm_psw" required>
        <label for="is_vermieter">Ich möchte mich auch als Vermieter registrieren:</label>
        <input type="checkbox" id="is_vermieter" name="is_vermieter">
        <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <button type="submit" class="btn">Registrierung abschicken</button>
        <button type="button" class="btn cancel" onclick="closePopup('popupregistrieren')">Abbrechen</button>
    </form>
</div>
