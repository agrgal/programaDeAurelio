-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 15, 2016 at 09:13 AM
-- Server version: 5.7.13-0ubuntu0.16.04.2
-- PHP Version: 7.0.8-0ubuntu0.16.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdtutoria`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_alumno`
--

CREATE TABLE `tb_alumno` (
  `idalumno` int(4) NOT NULL DEFAULT '0',
  `alumno` varchar(75) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Nombre del alumno, en la forma APELLIDOS [coma] NOMBRE ',
  `unidad` varchar(8) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Curso al que pertenece'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_alumno`
--

INSERT INTO `tb_alumno` (`idalumno`, `alumno`, `unidad`) VALUES
(1, 'Abad Benítez, Noelia', '1 ESO C'),
(2, 'Gómez Lomas, Antonio', '1 ESO B'),
(3, 'Cárdenas Jiménez, Antonio', '2 Bach A'),
(4, 'García Álvaro, Carmen', '1 ESO D'),
(5, 'Pérez Benítez, Marta', '2 Bach A'),
(6, 'Rodríguez Castle, Samantha', '2 Bach A');

-- --------------------------------------------------------

--
-- Table structure for table `tb_asignaciones`
--

CREATE TABLE `tb_asignaciones` (
  `idasignacion` int(10) NOT NULL COMMENT 'identificación de cada asignación',
  `profesor` int(2) NOT NULL COMMENT 'Id del profesor al que se le aplica la asignación',
  `materia` int(2) NOT NULL COMMENT 'Asignatura o materia que imparte',
  `datos` varchar(1000) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Datos de id. de alumnos o curso asociado',
  `descripcion` varchar(300) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Nombre corto que designa la asignación',
  `tutorada` tinyint(1) NOT NULL COMMENT 'Si está o no tutorada. Sí -->, No -->0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_asignaciones`
--

INSERT INTO `tb_asignaciones` (`idasignacion`, `profesor`, `materia`, `datos`, `descripcion`, `tutorada`) VALUES
(7, 16, 12, '1ESOF#203', 'ASIG: EDUC CIUDADANÍA Y LOS DERECHOS HUMANOS, PROF: MARíA DEL CARM ESPEJO GUTIéRREZ DE TENA, CURSOS:  1ESOF, 2CFGSA', 1),
(10, 21, 38, '1ESOE', 'ASIG: TECNOLOGÍA, PROF: AURELIO GALLARDO RODRíGUEZ, CURSOS:  1ESOB', 1),
(14, 8, 19, '1ESOE', 'ASIG: FRANCÉS, PROF: ALEJANDRA BELDA LOZANO, CURSOS:  1ESOE', 0),
(15, 8, 36, '131#412#431#482#532#536#603#679#717#721#730#732#816#845#867#925#936#951', 'ASIG: PROYECTO INTEGRADO, PROF: ALEJANDRA BELDA LOZANO, CURSOS:  1ESOA, 1ESOE', 0),
(16, 2, 14, '1ESOE', 'ASIG: EDUCACIÓN FÍSICA, PROF: YOLANDA ALCOBAS GARCíA, CURSOS:  1ESOE', 1),
(19, 14, 31, '1ESOE', 'ASIG: MATEMÁTICAS, PROF: FEDERICO DíAZ ROMERO, CURSOS:  1ESOE', 0),
(20, 14, 31, '1ESOF', 'ASIG: MATEMÁTICAS, PROF: FEDERICO DíAZ ROMERO, CURSOS:  1ESOF', 1),
(21, 2, 38, '1ESOC', 'ASIG: TECNOLOGÍA, PROF: YOLANDA ALCOBAS GARCíA, CURSOS:  1ESOC', 1),
(22, 21, 38, '1ESOC', 'ASIG: TECNOLOGÍA, PROF: AURELIO GALLARDO RODRíGUEZ, CURSOS:  1ESOC', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_asignaturas`
--

CREATE TABLE `tb_asignaturas` (
  `idmateria` int(2) NOT NULL DEFAULT '0',
  `Materias` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Nombre de la materia a impartir',
  `Abr` varchar(3) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Abreviatura: tres letras en mayúsculas (OPCIONAL)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_asignaturas`
--

INSERT INTO `tb_asignaturas` (`idmateria`, `Materias`, `Abr`) VALUES
(1, 'BIOLOGÍA Y GEOLOGÍA', 'BYG'),
(2, 'CAMBIOS SOCIALES Y DE GÉNERO', ''),
(3, 'CAMBIOS SOCIALES Y N.R.G.', ''),
(4, 'CIENCIAS NATURALES', 'CNA'),
(5, 'CIENCIAS PARA EL MUNDO CONTEMPORÁNEO', ''),
(6, 'CIENCIAS SOCIALES', 'CSO'),
(7, 'CULTURA CLÁSICA', ''),
(8, 'DIBUJO ARTÍSTICO', ''),
(9, 'DIBUJO TÉCNICO', ''),
(10, 'ECONOMíA', ''),
(11, 'ED. PLÁSTICA Y VISUAL', 'EPV'),
(12, 'EDUC CIUDADANÍA Y LOS DERECHOS HUMANOS', ''),
(13, 'EDUC ÉTICO-CÍVICA', ''),
(14, 'EDUCACIÓN FÍSICA', 'EF'),
(15, 'ELECTROTECNIA', ''),
(16, 'ENS. DE RELIGIÓN', ''),
(17, 'FILOSOFÍA', 'FIL'),
(18, 'FÍSICA Y QUÍMICA', 'FYQ'),
(19, 'FRANCÉS', 'FR'),
(20, 'GEOGRAFÍA', 'GEO'),
(21, 'GEOGRAFíA E HISTORIA', 'GEH'),
(22, 'GRIEGO', ''),
(23, 'HISTORIA DE ESPAÑA', ''),
(24, 'HISTORIA DE ESPAÑA', ''),
(25, 'HISTORIA DE LA FILOSOFÍA', ''),
(26, 'INFORMÁTICA', 'INF'),
(27, 'INGLES', 'ING'),
(28, 'LATÍN', ''),
(29, 'LENGUA CASTELLANA', 'LCL'),
(30, 'LENGUA CASTELLANA Y LITERATURA', 'LCL'),
(31, 'MATEMÁTICAS', 'MAT'),
(32, 'MATEMÁTICAS A', ''),
(33, 'MATEMÁTICAS B', ''),
(34, 'MÚSICA', 'MUS'),
(35, 'OPTATIVA', ''),
(36, 'PROYECTO INTEGRADO', 'PI'),
(37, 'RELIGIÓN O CULTURA RELIGIOSA', 'REL'),
(38, 'TECNOLOGÍA', 'TEC'),
(39, 'TECNOLOGÍA APLICADA', 'TAP'),
(40, 'TECNOLOGÍA DE LA INFORMACIÓN Y COMUNICACIÓN', 'TIC'),
(41, 'TECNOLOGÍA INDUSTRIAL', 'TIN'),
(42, 'VIDA MORAL', 'VMO'),
(43, 'FÍSICA', 'FIS'),
(44, 'QUÍMICA', 'QUI'),
(45, 'CIENCIAS DE LA TIERRA Y MEDIO AMBIENTE', 'CTM'),
(46, 'Ámbito Científico Tecnológico', 'ACT'),
(47, 'Ámbito Socio Lingüístico', 'ASL'),
(48, 'Ámbito Práctico', 'APR'),
(49, 'HISTORIA DEL MUNDO CONTEMPORÁNEO', 'HMC');

-- --------------------------------------------------------

--
-- Table structure for table `tb_edicionevaluaciones`
--

CREATE TABLE `tb_edicionevaluaciones` (
  `ideval` int(4) NOT NULL,
  `nombreeval` varchar(50) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Nombre de la próxima evaluación programada',
  `fechafin` date NOT NULL COMMENT 'Fecha tope para esa evaluación. En MYSQL formato AÑO-MES-DíA'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_edicionevaluaciones`
--

INSERT INTO `tb_edicionevaluaciones` (`ideval`, `nombreeval`, `fechafin`) VALUES
(1, 'Primera Evaluación', '2015-12-22'),
(2, 'Segunda Evaluación', '2016-03-20'),
(3, 'Tercera evaluación y final', '2016-06-24'),
(4, 'Septiembre', '2016-09-10'),
(5, 'Extraordinaria Primera', '0000-00-00'),
(6, 'Extraordinaria Segunda', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_itemsopiniones`
--

CREATE TABLE `tb_itemsopiniones` (
  `iditem` int(4) NOT NULL,
  `item` varchar(250) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Nombre del aspecto a evaluar',
  `grupo` varchar(50) COLLATE latin1_spanish_ci NOT NULL COMMENT 'GRUPO al que pertenece',
  `positivo` int(1) NOT NULL COMMENT '0 --> Negativo , 1 --> Positivo , 2-> NEUTRO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_itemsopiniones`
--

INSERT INTO `tb_itemsopiniones` (`iditem`, `item`, `grupo`, `positivo`) VALUES
(7, 'Muestra dificultades en aprendizajes básicos', 'DIFICULTADES', 0),
(8, 'Dificultades de relación e integración en el aula', 'DIFICULTADES', 0),
(9, 'Se distrae con frecuencia. No atiende', 'COMPORTAMIENTO', 0),
(10, 'Respeta al profesorado y a sus compañeros/as', 'COMPORTAMIENTO', 1),
(11, 'Su comportamiento en clase es negativo', 'COMPORTAMIENTO', 0),
(12, 'Muestra esfuerzo por aprender y superarse', 'ACTITUD', 1),
(13, 'Muestra actitud positiva en el aula y en el centro', 'ACTITUD', 1),
(14, 'Muestra abandono del área', 'ACTITUD', 0),
(17, 'No participa en clase', 'COMPORTAMIENTO', 0),
(18, 'No estudia', 'RENDIMIENTO Y TAREAS', 0),
(20, 'No trabaja con regularidad', 'RENDIMIENTO Y TAREAS', 0),
(21, 'Estudia', 'RENDIMIENTO Y TAREAS', 1),
(22, 'Trabaja con regularidad', 'RENDIMIENTO Y TAREAS', 1),
(23, 'Podría esforzarse más', 'ACTITUD', 0),
(24, 'Adaptar metodología e instr. de evaluación', 'SUPERAR DIFICULTADES', 2),
(25, 'Aplicar actividades de refuerzo y apoyo', 'SUPERAR DIFICULTADES', 2),
(26, 'Proponer actividades de ampliación', 'SUPERAR DIFICULTADES', 2),
(27, 'Mejorar métodos de estudio y hábitos de trabajo', 'SUPERAR DIFICULTADES', 2),
(28, 'Proponer estudio psicopedagógico o elaborar ACIs', 'SUPERAR DIFICULTADES', 2),
(29, 'Aumentar control periódico tareas-cuadernos', 'SUPERAR DIFICULTADES', 2),
(30, 'Incidir en el control del comportamiento en clase', 'SUPERAR DIFICULTADES', 2),
(31, 'Ampliar la acción tutorial y la orientación profesional', 'SUPERAR DIFICULTADES', 2),
(32, 'Proponer contrato educativo con familia-alumnado', 'SUPERAR DIFICULTADES', 2),
(33, 'Alta probabilidad de suspender (menos de 3)', 'NOTA ORIENTATIVA', 3),
(34, 'Posibilidad de suspender (3 ó 4)', 'NOTA ORIENTATIVA', 3),
(35, 'Aprobado (5 ó 6)', 'NOTA ORIENTATIVA', 3),
(36, 'Buen trabajo (6, 7 y 8)', 'NOTA ORIENTATIVA', 3),
(37, 'Trabajo excelente (entre 8 y 10)', 'NOTA ORIENTATIVA', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_opiniones`
--

CREATE TABLE `tb_opiniones` (
  `id` int(10) NOT NULL,
  `fecha` date NOT NULL COMMENT 'Fecha en la que se produce la opinión',
  `alumno` int(4) NOT NULL COMMENT 'Id del alumno',
  `asignacion` int(8) NOT NULL COMMENT 'Id de la asignación',
  `items` varchar(300) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Conjunto de items que sirve para calificar al alumno/a',
  `observaciones` blob NOT NULL COMMENT 'Opinión expresada por escrito'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_opiniones`
--

INSERT INTO `tb_opiniones` (`id`, `fecha`, `alumno`, `asignacion`, `items`, `observaciones`) VALUES
(104, '2015-03-15', 90, 10, '', 0x266c743b702667743b26616d703b6e6273703b6d6d20686a20676a6720686b6a266c743b2f702667743b),
(129, '2015-03-17', 653, 10, '', 0x266c743b702667743b66676820646668206466672068266c743b2f702667743b),
(135, '2015-03-16', 870, 10, '', 0x266c743b702667743b4361736920616c2066696e616c2e2e2e266c743b2f702667743b),
(136, '2015-03-16', 925, 10, '', 0x266c743b702667743b4d757920616c2066696e616c2e2e2e266c743b2f702667743b),
(137, '2015-03-16', 941, 10, '14#23', 0x266c743b702667743b41636162616e646f266c743b2f702667743b),
(138, '2015-03-16', 951, 10, '12#13', 0x266c743b702667743b5920616361626f2064266961637574653b612031362e2e2e266c743b2f702667743b),
(141, '2015-03-18', 160, 10, '', 0x266c743b702667743b64676420676466672066646766646726616d703b6e6273703b266c743b2f702667743b),
(167, '2015-04-13', 870, 10, '', 0x266c743b702667743b4361736920616c2066696e616c2e2e2e266c743b2f702667743b),
(169, '2015-04-13', 941, 10, '14#23', 0x266c743b702667743b41636162616e646f266c743b2f702667743b),
(170, '2015-04-13', 925, 10, '', 0x266c743b702667743b4d757920616c2066696e616c2e2e2e266c743b2f702667743b),
(171, '2015-04-13', 951, 10, '12#13', 0x266c743b702667743b5920616361626f2064266961637574653b612031362e2e2e20266e74696c64653b266e74696c64653b266e74696c64653b264e74696c64653b264e74696c64653b264e74696c64653b264e74696c64653b266161637574653b266561637574653b266961637574653b266f61637574653b2675756d6c3b266c743b2f702667743b),
(177, '2015-04-21', 951, 10, '12#13', 0x266c743b702667743b5920616361626f2064266961637574653b612031362e2e2e20266e74696c64653b266e74696c64653b266e74696c64653b264e74696c64653b264e74696c64653b264e74696c64653b264e74696c64653b266161637574653b266561637574653b266961637574653b266f61637574653b2675756d6c3b266c743b2f702667743b),
(185, '2015-10-03', 87, 14, '13', ''),
(194, '2016-05-10', 87, 14, '10#14#23', 0x266c743b702667743b416c676f266c743b2f702667743b),
(195, '2016-05-10', 951, 14, '11#12#18#20#21#22', 0x266c743b702667743b592061686f726120616c676f206d266161637574653b73266c743b2f702667743b),
(197, '2016-05-04', 951, 14, '11#12#18#20#21#22', 0x266c743b702667743b592061686f726120616c676f206d266161637574653b73266c743b2f702667743b),
(198, '2016-05-04', 87, 14, '10#14#23', 0x266c743b702667743b416c676f266c743b2f702667743b),
(200, '2016-05-10', 131, 15, '', 0x266c743b702667743b3131313131266c743b2f702667743b),
(201, '2016-05-10', 412, 15, '', 0x266c743b702667743b3232323232266c743b2f702667743b),
(202, '2016-05-10', 431, 15, '', 0x266c743b702667743b33333333266c743b2f702667743b),
(203, '2016-05-10', 482, 15, '', 0x266c743b702667743b3434343434266c743b2f702667743b),
(204, '2016-05-10', 532, 15, '13', 0x266c743b702667743b353535266c743b2f702667743b),
(205, '2016-05-10', 536, 15, '12', 0x266c743b702667743b3636363636266c743b2f702667743b),
(206, '2016-05-10', 951, 15, '', 0x266c743b702667743b747274347934343635266c743b2f702667743b),
(209, '2016-05-10', 87, 16, '7#8#12#13', ''),
(210, '2016-05-10', 90, 16, '12#13', ''),
(211, '2016-05-10', 160, 16, '10#12#13#21#22', ''),
(212, '2016-05-10', 184, 16, '33#34#36#37', ''),
(213, '2016-05-10', 951, 16, '13#14', 0x266c743b702667743b6766206a666a66266c743b2f702667743b),
(214, '2016-05-10', 941, 16, '12#23', 0x266c743b702667743b67682066676a20666a266c743b2f702667743b),
(215, '2016-05-10', 925, 16, '12#23', 0x266c743b702667743b676820666a6667206a266c743b2f702667743b),
(221, '2015-11-02', 87, 14, '8#14#35#36', ''),
(224, '2016-06-19', 951, 14, '8#13#14', ''),
(225, '2016-06-19', 941, 14, '12', 0x266c743b702667743b59206573637269626f266c743b2f702667743b),
(227, '2016-06-19', 951, 19, '10#12#13#21#22', 0x266c743b702667743b546f646f20657320706f73697469766f266c743b2f702667743b),
(228, '2016-06-19', 536, 19, '', 0x266c743b702667743b7920616c676f20656e2065737461266c743b2f702667743b),
(256, '2016-07-12', 1, 21, '12#13', 0x266c743b702667743b797520796a266c743b2f702667743b),
(258, '2016-07-12', 947, 21, '10#12#13#21#22', 0x266c743b702667743b676a20666726616d703b6e6273703b266c743b2f702667743b),
(259, '2016-07-12', 907, 21, '7#8#9#11#14#17#18#20#23', ''),
(260, '2016-07-17', 17, 19, '12#13', 0x266c743b702667743b417175266961637574653b207369207175652068617920616c676f2e2e2e266c743b2f702667743b),
(261, '2016-07-17', 17, 19, '12#14', ''),
(262, '2016-07-18', 17, 19, '10#12#13#21#22', ''),
(263, '2016-07-20', 155, 14, '12#14', ''),
(265, '2016-07-20', 1, 19, '10#12', ''),
(266, '2016-07-20', 31, 19, '12#13', ''),
(272, '2016-07-21', 87, 19, '7#13#14#23', ''),
(277, '2016-06-19', 49, 22, '13#23', 0x266c743b702667743b6868647366686861732068266c743b2f702667743b),
(278, '2016-07-26', 1, 22, '13', 0x266c743b702667743b31266c743b2f702667743b),
(279, '2016-07-26', 31, 22, '14', 0x266c743b702667743b32266c743b2f702667743b),
(280, '2016-07-26', 34, 22, '12#23', ''),
(281, '2016-07-26', 947, 10, '12#14', 0x266c743b702667743b6664672073646620677364666720736466266c743b2f702667743b),
(282, '2016-07-19', 1, 22, '13', 0x266c743b702667743b31266c743b2f702667743b),
(283, '2016-07-19', 947, 10, '12', 0x266c743b702667743b6664672073646620677364666720736466266c743b2f702667743b),
(284, '2016-07-19', 34, 22, '12#23', ''),
(285, '2016-07-19', 31, 22, '14', 0x266c743b702667743b32266c743b2f702667743b),
(286, '2016-07-26', 9, 10, '14#33#34#35', 0x266c743b702667743b4f70696e69266f61637574653b6e207072696d657261266c743b2f702667743b),
(287, '2016-07-26', 951, 10, '13#14', 0x266c743b702667743b265561637574653b6c74696d6f266c743b2f702667743b),
(288, '2016-07-26', 49, 22, '13#23', 0x266c743b702667743b4f7472612076657a266c743b2f702667743b),
(290, '2016-07-25', 49, 22, '13#23', 0x266c743b702667743b4f7472612076657a266c743b2f702667743b),
(291, '2016-07-25', 951, 10, '13#14', 0x266c743b702667743b265561637574653b6c74696d6f266c743b2f702667743b);

-- --------------------------------------------------------

--
-- Table structure for table `tb_opiniongeneral`
--

CREATE TABLE `tb_opiniongeneral` (
  `idopiniongeneral` int(10) NOT NULL,
  `eval` int(4) NOT NULL,
  `asignacion` int(8) NOT NULL,
  `opinion` blob NOT NULL,
  `actuaciones` blob NOT NULL,
  `mejora` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_opiniongeneral`
--

INSERT INTO `tb_opiniongeneral` (`idopiniongeneral`, `eval`, `asignacion`, `opinion`, `actuaciones`, `mejora`) VALUES
(1, 1, 10, 0x266c743b702667743b4573746120636c6173652070726573656e7461206d7563686173206469666963756c746164657320646520636f6e63656e7472616369266f61637574653b6e2e2e2e266c743b2f702667743b, 0x266c743b702667743b5265616c697a616369266f61637574653b6e20646520656a6572636963696f7320636f6d706c656d656e746172696f73266c743b2f702667743b, 0x266c743b702667743b496e73697374697220656e206c61732074617265617320646520636173612e266c743b2f702667743b),
(3, 1, 16, 0x266c743b702667743b61266c743b2f702667743b, 0x266c743b702667743b62266c743b2f702667743b, 0x266c743b702667743b63266c743b2f702667743b),
(4, 2, 16, 0x266c743b702667743b647366736466266c743b2f702667743b, 0x266c743b702667743b736466736466266c743b2f702667743b, 0x266c743b702667743b736466736466266c743b2f702667743b),
(5, 3, 16, 0x266c743b702667743b266e74696c64653b266e74696c64653b266e74696c64653b266e74696c64653b266c743b2f702667743b, 0x266c743b702667743b266e74696c64653b266e74696c64653b266e74696c64653b266e74696c64653b266e74696c64653b266c743b2f702667743b, 0x266c743b702667743b266161637574653b266561637574653b266961637574653b266f61637574653b267561637574653b2675756d6c3b264e74696c64653b266e74696c64653b266c743b2f702667743b),
(6, 3, 10, 0x266c743b702667743b54656e676f2071756520657363726962697220616c676f207175652073656120637265266961637574653b626c652c2070617261207175652073616c676120656e20656c20617061727461646f20636f72726573706f6e6469656e74652e266c743b2f702667743b, 0x266c743b702667743b4375616e746f206d266161637574653b7320657363726962612c206d656a6f7220766572266561637574653b206c6f7320726573756c7461646f73207175652073616c656e20656e206c61207461626c61266c743b2f702667743b, ''),
(7, 3, 14, 0x266c743b702667743b53266961637574653b2c2061686f7261206465626f20657363726962697220616c676f206d266161637574653b7320706f727175652e2e266c743b2f702667743b, 0x266c743b702667743b4e6f207661796120612073657220717565206e6f2070756564612e2e2e266c743b2f702667743b, 0x266c743b702667743b436f6e736567756972206f747261206d616e6572612064652068616365726c6f2e2e2e26616d703b6e6273703b266c743b2f702667743b),
(8, 1, 14, 0x266c743b702667743b31206672616e63266561637574653b7326616d703b6e6273703b26616d703b6e6273703b26616d703b6e6273703b26616d703b6e6273703b26616d703b6e6273703b26616d703b6e6273703b26616d703b6e6273703b26616d703b6e6273703b266c743b2f702667743b, 0x266c743b702667743b32206672616e63266561637574653b73266c743b2f702667743b, 0x266c743b702667743b33206672616e63266561637574653b73266c743b2f702667743b),
(9, 3, 19, 0x266c743b702667743b5175697a266161637574653b732c207175697a266161637574653b732c207175697a266161637574653b73266c743b2f702667743b, 0x266c743b702667743b6768676668686a66676a206a686a266c743b2f702667743b, 0x266c743b702667743b67686a20686a2066746a20676820676820666726616d703b6e6273703b266c743b2f702667743b);

-- --------------------------------------------------------

--
-- Table structure for table `tb_ord_incidencias`
--

CREATE TABLE `tb_ord_incidencias` (
  `Idincidencia` int(10) NOT NULL,
  `N` int(3) NOT NULL DEFAULT '0',
  `Serial_number` varchar(23) DEFAULT NULL,
  `SNID` int(11) DEFAULT NULL,
  `Planta` varchar(1) DEFAULT NULL,
  `Carro` int(2) DEFAULT NULL,
  `alumno1` varchar(100) NOT NULL,
  `alumno2` varchar(100) NOT NULL,
  `alumno3` varchar(100) NOT NULL,
  `Curso` varchar(5) NOT NULL,
  `Incidencia` text NOT NULL,
  `profesor` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `Hora` int(1) NOT NULL,
  `Resuelto` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Número de serie de los carros';

--
-- Dumping data for table `tb_ord_incidencias`
--

INSERT INTO `tb_ord_incidencias` (`Idincidencia`, `N`, `Serial_number`, `SNID`, `Planta`, `Carro`, `alumno1`, `alumno2`, `alumno3`, `Curso`, `Incidencia`, `profesor`, `fecha`, `Hora`, `Resuelto`) VALUES
(3, 118, 'LXTT90C0029380B7061601 ', 2147483647, '0', 14, 'Yo', 'Yo', '', '15', 'Se le ha ido la batería (luz de carga naranja parpadeante conectado más de un día y no carga) El ordenador en otro transformador no carga. La misma batería conectada a otro ordenador en otro transformador no carga. Su transformador si carga a otro ordenador, por lo que se deduce que la batería está estropeada.', 'Aurelio Gallardo', '2011-03-02', 2, 1),
(4, 42, 'LXTT90C002938089E31601 ', 2147483647, '2', 5, '', '', '', '16', 'Signos externos: al conectar el transformador de carga no se queda encendido piloto verde. Los chicos/as se quejan de que les dura muy poco encendido. Monitor de baterías en Guadalinex: aparecen dos símbolos, como si tuviese dos baterías; uno corresponde a la batería modelo BODEN34, de Sanyo, y aparece siempre como descargada; la otra cargada, modelo GARDA43 de SONY. Cambio de transformador de carga: no arregla el problema. Deduzco que es un fallo de la batería en la unidad. ', 'Aurelio Gallardo Rodríguez', '2011-03-24', 1, 1),
(5, 82, 'LXTT90C0029380C4DC1601 ', 2147483647, '1', 10, '', '', '', '0', 'El ordenador no funciona porque...', 'fgfdgsdf', '2011-11-09', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_profesores`
--

CREATE TABLE `tb_profesores` (
  `idprofesor` int(3) NOT NULL DEFAULT '0',
  `Empleado` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Nombre del empleado de la forma: APELLIDOS [coma] NOMBRE',
  `DNI` varchar(9) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'NIF: DNI + letra',
  `IDEA` varchar(10) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Usuario IDEA Junta Andalucía',
  `tutorde` varchar(8) COLLATE latin1_spanish_ci DEFAULT NULL COMMENT 'Si se sabe cuál es su tutoría...',
  `email` varchar(200) COLLATE latin1_spanish_ci DEFAULT 'correo@prueba.es' COMMENT 'Dirección de email',
  `administrador` int(1) NOT NULL DEFAULT '0' COMMENT '0 si no es administrador, 1 si lo es'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Dumping data for table `tb_profesores`
--

INSERT INTO `tb_profesores` (`idprofesor`, `Empleado`, `DNI`, `IDEA`, `tutorde`, `email`, `administrador`) VALUES
(21, 'Gallardo Rodríguez, Aurelio', '45678921F', 'fghtyui456', '', 'inf2bacseritium@gmail.com', 0);
--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_alumno`
--
ALTER TABLE `tb_alumno`
  ADD PRIMARY KEY (`idalumno`);

--
-- Indexes for table `tb_asignaciones`
--
ALTER TABLE `tb_asignaciones`
  ADD PRIMARY KEY (`idasignacion`);

--
-- Indexes for table `tb_asignaturas`
--
ALTER TABLE `tb_asignaturas`
  ADD PRIMARY KEY (`idmateria`);

--
-- Indexes for table `tb_edicionevaluaciones`
--
ALTER TABLE `tb_edicionevaluaciones`
  ADD PRIMARY KEY (`ideval`);

--
-- Indexes for table `tb_itemsopiniones`
--
ALTER TABLE `tb_itemsopiniones`
  ADD PRIMARY KEY (`iditem`);

--
-- Indexes for table `tb_opiniones`
--
ALTER TABLE `tb_opiniones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_opiniongeneral`
--
ALTER TABLE `tb_opiniongeneral`
  ADD PRIMARY KEY (`idopiniongeneral`);

--
-- Indexes for table `tb_ord_incidencias`
--
ALTER TABLE `tb_ord_incidencias`
  ADD PRIMARY KEY (`Idincidencia`);

--
-- Indexes for table `tb_profesores`
--
ALTER TABLE `tb_profesores`
  ADD PRIMARY KEY (`idprofesor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_asignaciones`
--
ALTER TABLE `tb_asignaciones`
  MODIFY `idasignacion` int(10) NOT NULL AUTO_INCREMENT COMMENT 'identificación de cada asignación', AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `tb_edicionevaluaciones`
--
ALTER TABLE `tb_edicionevaluaciones`
  MODIFY `ideval` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_opiniones`
--
ALTER TABLE `tb_opiniones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;
--
-- AUTO_INCREMENT for table `tb_opiniongeneral`
--
ALTER TABLE `tb_opiniongeneral`
  MODIFY `idopiniongeneral` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tb_ord_incidencias`
--
ALTER TABLE `tb_ord_incidencias`
  MODIFY `Idincidencia` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
