<body>
    <main>
        <!-- contenu principal -->
    </main>
    <footer>
        <div class="footer-content">
            <?php if (isset($_SESSION['messageconnexion'])) {
                echo "<p class='message-connexion'> " . $_SESSION['messageconnexion'] . " </p>";
            }
            ?>
        </div>
    </footer>
</body>

</html>