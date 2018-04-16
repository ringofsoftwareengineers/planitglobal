<?php

echo password_hash("123", PASSWORD_BCRYPT);


echo '<br>';
echo password_verify("123", '$2y$10$MJHAg.1DRJP6tXJPydokJ.LWxOzs3bZNSlLt3Lb4vusD2IYZWjSfm');

echo '<br>';
echo password_verify("123", '$2y$10$AlQ8LHvS4EcvAdL8Jk8zMuHa873.w0648E3f5cFKZu0Dzvpwh8NWO');