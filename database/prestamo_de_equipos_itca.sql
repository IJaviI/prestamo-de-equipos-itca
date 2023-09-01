CREATE DATABASE prestamo_de_equipos_itca;

USE prestamo_de_equipos_itca;

CREATE TABLE depto (
  id_depto INT(11) NOT NULL AUTO_INCREMENT,
  depto VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_depto)
) AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;


CREATE TABLE docente (
  id_docente INT(11) NOT NULL AUTO_INCREMENT,
  carnet VARCHAR(20) NOT NULL,
  nom_docente VARCHAR(30) NOT NULL DEFAULT '',
  ape_docente VARCHAR(30) NOT NULL DEFAULT '',
  tipo VARCHAR(30) DEFAULT NULL,
  telcasa VARCHAR(9) DEFAULT NULL,
  celular VARCHAR(9) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  estado VARCHAR(20) DEFAULT NULL,
  clave VARCHAR(50) DEFAULT NULL,
  imagen VARCHAR(200) DEFAULT NULL,
  id_depto INT(2) DEFAULT NULL,
  accesosistemas INT(4) DEFAULT '1' COMMENT '1=si 0=no',
  esadministrador INT(1) DEFAULT '0' COMMENT '1=si 0=no 3=soporte',
  PRIMARY KEY (id_docente),
  UNIQUE KEY id_docente (id_docente),
  FOREIGN KEY (id_depto)
  REFERENCES prestamo_de_equipos_itca.depto (id_depto)
) AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

SELECT * FROM docente
SELECT * FROM aula
DELETE FROM docente WHERE id_docente = 50

CREATE TABLE marca (
  id_marca INT(11) NOT NULL AUTO_INCREMENT,
  marca VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id_marca)
) AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

CREATE TABLE equipo (
  id_equipo INT(11) NOT NULL AUTO_INCREMENT,
  equipo VARCHAR(50) DEFAULT NULL,
  n_serie VARCHAR(50) DEFAULT NULL,
  fecha_ingreso DATE DEFAULT NULL,
  estado VARCHAR(25) DEFAULT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  modelo VARCHAR(50) DEFAULT NULL,
  stock INT(11) DEFAULT NULL,
  id_marca INT(11) DEFAULT NULL,
  id_depto INT(11) DEFAULT NULL,
  PRIMARY KEY (id_equipo),
  FOREIGN KEY (id_marca)
  REFERENCES prestamo_de_equipos_itca.marca (id_marca),
  FOREIGN KEY (id_depto)
  REFERENCES prestamo_de_equipos_itca.depto (id_depto)
) AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

CREATE TABLE aula (
  id_aula INT(11) NOT NULL AUTO_INCREMENT,
  aula VARCHAR(50) DEFAULT NULL,
  ubicacion VARCHAR(100) DEFAULT NULL, 
  descripcion VARCHAR(255) DEFAULT NULL,
  tipo VARCHAR(50) DEFAULT NULL COMMENT 'cambiar a int', 
  PRIMARY KEY (id_aula)
) AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

CREATE TABLE prestamo (
  id_prestamo INT(11) NOT NULL AUTO_INCREMENT,
  id_docente INT(11) DEFAULT NULL,
  id_aula INT(11) DEFAULT NULL,
  estado VARCHAR(50) DEFAULT NULL,
  fecha_hecha DATE DEFAULT NULL,
  fecha_destino DATE DEFAULT NULL,
  PRIMARY KEY (id_prestamo),
  FOREIGN KEY (id_docente)
  REFERENCES prestamo_de_equipos_itca.docente (id_docente),
  FOREIGN KEY (id_aula)
  REFERENCES prestamo_de_equipos_itca.aula (id_aula)
) AUTO_INCREMENT=392 DEFAULT CHARSET=latin1;

SELECT * FROM docente

CREATE TABLE det_prestamo (
  id_det_prestamo INT(11) NOT NULL AUTO_INCREMENT,
  id_prestamo INT(11) DEFAULT NULL,
  id_equipo INT(11) DEFAULT NULL,
  estado VARCHAR(20) DEFAULT NULL,
  inicio TIME DEFAULT NULL,
  fin TIME DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  PRIMARY KEY (id_det_prestamo),
  FOREIGN KEY (id_prestamo)
  REFERENCES prestamo_de_equipos_itca.prestamo (id_prestamo),
  FOREIGN KEY (id_equipo)
  REFERENCES prestamo_de_equipos_itca.equipo (id_equipo)
) AUTO_INCREMENT=392 DEFAULT CHARSET=latin1;
