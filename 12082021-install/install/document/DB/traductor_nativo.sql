
DROP FUNCTION IF EXISTS `fun_agregar_palabra`;
DELIMITER $$


CREATE FUNCTION `fun_agregar_palabra` (`p_palabra` VARCHAR(255), `p_id_tipo` INT, `p_id_idioma` INT) RETURNS BIT(1) BEGIN 
	DECLARE v BIT;
	SET v = IF((SELECT COUNT(*) FROM palabras t1 WHERE t1.palabra = p_palabra AND t1.id_idioma = p_id_idioma) = 0, 1, 0);
	IF (v = 1) then
		INSERT INTO palabras(palabra, id_tipo, id_idioma)
		VALUES(UPPER(p_palabra), p_id_tipo, p_id_idioma); 
		RETURN 1;
	ELSE
		RETURN 0;
	END IF;
END$$

DELIMITER ;


DROP TABLE IF EXISTS idiomas;
CREATE TABLE IF NOT EXISTS  idiomas (
  id int(3) NOT NULL,
  idioma varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  composicion_prosa varchar(255) COLLATE utf8_spanish_ci NOT NULL
);


INSERT INTO `idiomas` (`id`, `idioma`, `composicion_prosa`) VALUES
(1, 'español', '1,2,3,4,5,6,7,8,9,10,'),
(4, 'tucano', '4,10,9,8,7,6,5,3,2,1,');


DROP TABLE IF EXISTS `palabras`;
CREATE TABLE IF NOT EXISTS `palabras` (
  `id` int(11) NOT NULL,
  `palabra` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_tipo` int(2) NOT NULL,
  `id_idioma` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;




DROP TABLE IF EXISTS `relacion_palabras`;
CREATE TABLE IF NOT EXISTS `relacion_palabras` (
  `id` int(11) NOT NULL,
  `id_palabra_origen` int(11) NOT NULL,
  `id_palabra_traduccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;




DROP TABLE IF EXISTS `tipos`;
CREATE TABLE IF NOT EXISTS  `tipos` (
  `id` int(2) NOT NULL,
  `tipo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(3) NOT NULL,
  `usuario` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `contrasena` varchar(255) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;



INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`) VALUES
(1, 'admin', '81dc9bdb52d04dc20036dbd8313ed055');


CREATE TABLE  `vista_idiomas` (
`conteo` bigint(21)
,`idioma` varchar(255)
);


CREATE TABLE `vista_palabras` (
`conteo` bigint(21)
,`palabra` varchar(255)
);

DROP TABLE IF EXISTS `vista_idiomas`;

CREATE VIEW `vista_idiomas`  AS SELECT count(0) AS `conteo`, `t1`.`idioma` AS `idioma` FROM `idiomas` AS `t1` WHERE `t1`.`id` <> 0 GROUP BY `t1`.`idioma` ;


DROP TABLE IF EXISTS `vista_palabras`;

CREATE  VIEW `vista_palabras`  AS SELECT count(0) AS `conteo`, `t1`.`palabra` AS `palabra` FROM `palabras` AS `t1` WHERE `t1`.`id` <> 0 GROUP BY `t1`.`palabra` ;


ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `palabras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_id_idioma` (`id_idioma`),
  ADD KEY `FK_id_tipo` (`id_tipo`);


ALTER TABLE `relacion_palabras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_id_palabra` (`id_palabra_origen`,`id_palabra_traduccion`),
  ADD KEY `id_palabra_traduccion` (`id_palabra_traduccion`);


ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `idiomas`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `palabras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;

ALTER TABLE `relacion_palabras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;


ALTER TABLE `tipos`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;


ALTER TABLE `usuarios`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `palabras`
  ADD CONSTRAINT `palabras_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `palabras_ibfk_2` FOREIGN KEY (`id_idioma`) REFERENCES `idiomas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `relacion_palabras`
  ADD CONSTRAINT `relacion_palabras_ibfk_1` FOREIGN KEY (`id_palabra_origen`) REFERENCES `palabras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relacion_palabras_ibfk_2` FOREIGN KEY (`id_palabra_traduccion`) REFERENCES `palabras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

