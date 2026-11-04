<?php

return [

    /*
     * Durée d'inactivité, en minutes, après laquelle la session du
     * back-office se ferme (sauf connexion avec « Se souvenir de moi »).
     */
    'idle_minutes' => (int) env('ADMIN_IDLE_MINUTES', 60),

];
