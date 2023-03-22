INSERT INTO `vat_rate` (`id`, `name`) VALUES
    (2, 'Taux intermédiaire'),
    (1, 'Taux normal'),
    (5, 'Taux nul'),
    (4, 'Taux particulier'),
    (3, 'Taux réduit');

INSERT INTO `vat_value` (`id`, `rate_id`, `value`, `end_at`) VALUES
    (1, 1, 1960, '2014-01-01'),
    (2, 1, 2000, NULL),
    (3, 2, 700, '2014-01-01'),
    (4, 2, 1000, NULL),
    (5, 3, 550, NULL),
    (6, 4, 210, NULL),
    (7, 5, 0, NULL);