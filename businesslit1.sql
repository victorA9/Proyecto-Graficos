-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2025 a las 01:44:38
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `businesslit1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `box`
--

CREATE TABLE `box` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `brand`
--

INSERT INTO `brand` (`id`, `image`, `name`, `description`, `created_at`) VALUES
(1, NULL, 'CF MOTO', NULL, '2025-03-25 14:28:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `buy_orders`
--

CREATE TABLE `buy_orders` (
  `id` int(11) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pendiente','firmada','procesada','completada') NOT NULL DEFAULT 'pendiente',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `signed_at` timestamp NULL DEFAULT NULL,
  `signed_by` int(11) DEFAULT NULL,
  `delivery_status` enum('pendiente','entregado') NOT NULL DEFAULT 'pendiente',
  `payment_status` enum('pendiente','pagado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `buy_orders`
--

INSERT INTO `buy_orders` (`id`, `pdf_path`, `total`, `status`, `created_by`, `created_at`, `signed_at`, `signed_by`, `delivery_status`, `payment_status`) VALUES
(20, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1745714122_1.pdf', '2143.68', '', 1, '2025-04-27 00:35:22', '2025-04-27 00:47:05', NULL, 'pendiente', 'pendiente'),
(22, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1745801082_1.pdf', '2143.68', 'completada', 1, '2025-04-28 00:44:43', '2025-04-28 00:44:51', 1, 'entregado', 'pagado'),
(23, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1745802339_1.pdf', '2143.68', 'completada', 1, '2025-04-28 01:05:39', '2025-04-28 01:05:44', 1, 'entregado', 'pagado'),
(24, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1745889907_1.pdf', '2143.68', 'completada', 1, '2025-04-29 01:25:08', '2025-04-29 01:25:18', 1, 'entregado', 'pagado'),
(26, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1745890349_1.pdf', '2143.68', 'completada', 1, '2025-04-29 01:32:29', '2025-04-29 01:32:35', 1, 'entregado', 'pagado'),
(27, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1746408365_1.pdf', '2143.68', 'completada', 1, '2025-05-05 01:26:06', '2025-05-05 01:26:56', 1, 'entregado', 'pagado'),
(28, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1746408619_1.pdf', '2143.68', 'completada', 1, '2025-05-05 01:30:19', '2025-05-05 01:30:24', 1, 'entregado', 'pagado'),
(29, 'signed_order_1746410597_1.pdf', '2143.68', 'completada', 1, '2025-05-05 02:03:17', '2025-05-05 02:14:18', 1, 'entregado', 'pagado'),
(30, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1746411952_1.pdf', '2143.68', 'pendiente', 1, '2025-05-05 02:25:52', NULL, NULL, 'pendiente', 'pendiente'),
(31, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1746417950_1.pdf', '2143.68', 'pendiente', 1, '2025-05-05 04:05:50', NULL, NULL, 'pendiente', 'pendiente'),
(32, 'C:xampphtdocsBUSINESSLITcoreappaction/../../storage/orders/order_1746487581_1.pdf', '2143.68', 'pendiente', 1, '2025-05-05 23:26:21', NULL, NULL, 'pendiente', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuration`
--

CREATE TABLE `configuration` (
  `id` int(11) NOT NULL,
  `short` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `kind` int(11) NOT NULL,
  `val` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuration`
--

INSERT INTO `configuration` (`id`, `short`, `name`, `kind`, `val`) VALUES
(1, 'company_name', 'Nombre de la empresa', 2, 'Almacen CJJ'),
(2, 'title', 'Titulo del Sistema', 2, 'Almacen CJJ'),
(3, 'ticket_title', 'Titulo en el Ticket', 2, 'Almacen CJJ'),
(4, 'admin_email', 'Email Administracion', 2, ''),
(5, 'report_image', 'Imagen en Reportes', 4, ''),
(6, 'imp-name', 'Nombre Impuesto', 2, 'IVA'),
(7, 'imp-val', 'Valor Impuesto (%)', 2, '16'),
(8, 'currency', 'Simbolo de Moneda', 2, '$');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `d`
--

CREATE TABLE `d` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `d`
--

INSERT INTO `d` (`id`, `name`) VALUES
(1, 'Entregado'),
(2, 'Pendiente'),
(3, 'Cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `f`
--

CREATE TABLE `f` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `f`
--

INSERT INTO `f` (`id`, `name`) VALUES
(1, 'Efectivo'),
(2, 'Deposito'),
(3, 'Cheque');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `message`
--

INSERT INTO `message` (`id`, `code`, `message`, `user_from`, `user_to`, `is_read`, `created_at`) VALUES
(1, 'tvnsOwwOcNiOhUT', 'Hola victor jugo', 1, 2, 1, '2025-03-25 15:02:49'),
(2, 'tvnsOwwOcNiOhUT', 'Hola administrador', 2, 1, 1, '2025-03-25 15:03:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operation`
--

CREATE TABLE `operation` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_destination_id` int(11) DEFAULT NULL,
  `operation_from_id` int(11) DEFAULT NULL,
  `q` float NOT NULL,
  `price_in` double DEFAULT NULL,
  `price_out` double DEFAULT NULL,
  `operation_type_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `is_draft` tinyint(1) NOT NULL DEFAULT 0,
  `is_traspase` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `operation`
--

INSERT INTO `operation` (`id`, `product_id`, `stock_id`, `stock_destination_id`, `operation_from_id`, `q`, `price_in`, `price_out`, `operation_type_id`, `sell_id`, `status`, `is_draft`, `is_traspase`, `created_at`) VALUES
(10, 2, 1, NULL, NULL, 1, 117520, 142500, 2, 10, 1, 0, 0, '2025-03-25 14:36:18'),
(12, 7, 1, NULL, NULL, 1, 221000, 268000, 2, 12, 1, 0, 0, '2025-03-25 14:45:12'),
(13, 9, 1, NULL, NULL, 2, 123000, 156000, 2, 13, 1, 0, 0, '2025-03-25 14:47:08'),
(14, 10, 1, NULL, NULL, 2, 64000, 78000, 2, 14, 1, 0, 0, '2025-03-25 14:48:05'),
(15, 6, 1, NULL, NULL, 1, 112000, 143000, 2, 15, 1, 1, 0, '2025-03-25 14:49:18'),
(16, 2, 1, NULL, NULL, 5, 117520, 142500, 2, 16, 1, 0, 0, '2025-03-25 14:49:50'),
(17, 3, 1, NULL, NULL, 1, 224500, 289000, 2, 17, 1, 1, 0, '2025-03-25 14:50:09'),
(18, 6, 1, NULL, NULL, 1, 112000, 143000, 2, 18, 1, 0, 0, '2025-03-25 14:50:25'),
(19, 8, 1, NULL, NULL, 12, 143000, 179000, 2, 19, 1, 0, 0, '2025-03-25 14:50:47'),
(20, 2, 1, NULL, NULL, 1, 117520, 142500, 2, 20, 1, 0, 0, '2025-03-25 14:51:00'),
(23, 10, 1, NULL, NULL, 1, 64000, 78000, 1, NULL, 1, 0, 0, '2025-03-26 14:13:58'),
(24, 10, 1, NULL, NULL, 1, 64000, 78000, 2, NULL, 1, 0, 0, '2025-03-26 14:14:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operation_type`
--

CREATE TABLE `operation_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `operation_type`
--

INSERT INTO `operation_type` (`id`, `name`) VALUES
(1, 'entrada'),
(2, 'salida'),
(3, 'entrada-pendiente'),
(4, 'salida-pendiente'),
(5, 'devolucion'),
(6, 'traspaso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p`
--

CREATE TABLE `p` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `p`
--

INSERT INTO `p` (`id`, `name`) VALUES
(1, 'Pagado'),
(2, 'Pendiente'),
(3, 'Cancelado'),
(4, 'Credito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `person_id` int(11) NOT NULL,
  `val` double DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `payment_type`
--

INSERT INTO `payment_type` (`id`, `name`) VALUES
(1, 'Cargo'),
(2, 'Abono');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `no` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `address1` varchar(50) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `phone1` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `email1` varchar(50) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `is_active_access` tinyint(1) NOT NULL DEFAULT 0,
  `has_credit` tinyint(1) NOT NULL DEFAULT 0,
  `credit_limit` double DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `kind` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price`
--

CREATE TABLE `price` (
  `id` int(11) NOT NULL,
  `price_out` double DEFAULT 0,
  `product_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `inventary_min` int(11) NOT NULL DEFAULT 10,
  `price_in` float NOT NULL,
  `price_out` float DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `presentation` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `width` float DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `expire_at` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `kind` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `image`, `code`, `barcode`, `name`, `description`, `inventary_min`, `price_in`, `price_out`, `unit`, `presentation`, `user_id`, `category_id`, `brand_id`, `width`, `height`, `weight`, `expire_at`, `created_at`, `kind`, `is_active`) VALUES
(1, '', '0001', '1234', '450 MT Gris', 'Moto gris', 10, 117520, 142500, '1', '', 1, NULL, NULL, 88, 126, 195, '0000-00-00', '2025-03-25 14:19:21', 1, 1),
(2, '', '0002', '12345', '450 MT Azul', 'Moto azul', 10, 117520, 142500, '1', '', 1, NULL, NULL, 88, 126, 195, '0000-00-00', '2025-03-25 14:20:30', 1, 1),
(3, '', '0003', '123456', '800 MTX', '', 10, 224500, 289000, '1', '', 1, NULL, NULL, 92, 135, 225, '0000-00-00', '2025-03-25 14:21:45', 1, 1),
(4, '', '0004', '1234567', '450 SR', 'Moto deportiva', 10, 112500, 146000, '1', '', 1, NULL, NULL, 76, 99, 185, '0000-00-00', '2025-03-25 14:23:27', 1, 1),
(5, '', '0005', '12345678', '450 CL-C', '', 10, 114000, 141000, '1', '', 1, NULL, NULL, 79, 110, 175, '0000-00-00', '2025-03-25 14:25:07', 1, 1),
(6, '', '0006', '123456789', '450 NK', '', 10, 112000, 143000, '1', '', 1, NULL, NULL, 76, 110, 185, '0000-00-00', '2025-03-25 14:26:26', 1, 1),
(7, '', '0007', '2121', '800 MT Explore Edition', '', 10, 221000, 268000, '1', '', 1, NULL, NULL, 92, 135, 225, '0000-00-00', '2025-03-25 14:30:51', 1, 1),
(8, '', '0008', '6565', '700 MT', '', 10, 143000, 179000, '1', '', 1, NULL, NULL, 76, 110, 195, '0000-00-00', '2025-03-25 14:32:27', 1, 1),
(9, '', '0009', '8787', '450 SR-S', '', 10, 123000, 156000, '1', '', 1, NULL, NULL, 76, 99, 175, '0000-00-00', '2025-03-25 14:33:39', 1, 1),
(10, '', '0010', '9898', '250 NK', '', 10, 64000, 78000, '1', '', 1, NULL, NULL, 0, 0, 0, '0000-00-00', '2025-03-25 14:34:21', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saving`
--

CREATE TABLE `saving` (
  `id` int(11) NOT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date_at` date DEFAULT NULL,
  `kind` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sell`
--

CREATE TABLE `sell` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(255) DEFAULT NULL,
  `invoice_file` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `sell_from_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `operation_type_id` int(11) DEFAULT 2,
  `box_id` int(11) DEFAULT NULL,
  `p_id` int(11) DEFAULT NULL,
  `d_id` int(11) DEFAULT NULL,
  `f_id` int(11) DEFAULT NULL,
  `total` double DEFAULT NULL,
  `cash` double DEFAULT NULL,
  `iva` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `is_draft` tinyint(1) NOT NULL DEFAULT 0,
  `stock_to_id` int(11) DEFAULT NULL,
  `stock_from_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sell`
--

INSERT INTO `sell` (`id`, `invoice_code`, `invoice_file`, `comment`, `ref_id`, `sell_from_id`, `person_id`, `user_id`, `operation_type_id`, `box_id`, `p_id`, `d_id`, `f_id`, `total`, `cash`, `iva`, `discount`, `is_draft`, `stock_to_id`, `stock_from_id`, `status`, `created_at`) VALUES
(10, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 1, 1, NULL, 142500, 142500, 16, 0, 0, 1, NULL, 1, '2025-03-25 14:36:18'),
(12, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 4, 1, NULL, 268000, 268000, 16, 10000, 0, 1, NULL, 1, '2025-03-25 14:45:12'),
(13, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 2, 1, NULL, 312000, 282000, 16, 30000, 0, 1, NULL, 1, '2025-03-25 14:47:08'),
(14, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 1, 2, NULL, 156000, 156000, 16, 0, 0, 1, NULL, 1, '2025-03-25 14:48:05'),
(15, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 2, 2, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2025-03-25 14:49:18'),
(16, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 4, 1, NULL, 712500, 712500, 16, 0, 0, 1, NULL, 1, '2025-03-25 14:49:50'),
(17, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 2, 2, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2025-03-25 14:50:09'),
(18, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 1, 1, NULL, 143000, 143000, 16, 0, 0, 1, NULL, 1, '2025-03-25 14:50:25'),
(19, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 4, 2, NULL, 2148000, 1952000, 16, 200000, 0, 1, NULL, 1, '2025-03-25 14:50:47'),
(20, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, 1, 1, NULL, 142500, 142500, 16, 0, 0, 1, NULL, 1, '2025-03-25 14:51:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spend`
--

CREATE TABLE `spend` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` double DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_principal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id`, `name`, `address`, `phone`, `email`, `is_principal`) VALUES
(1, 'Principal', NULL, NULL, NULL, 1),
(2, 'Almacen 1', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `comision` float DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `kind` int(11) NOT NULL DEFAULT 1,
  `stock_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `facial_data` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `name`, `lastname`, `username`, `email`, `password`, `image`, `comision`, `status`, `kind`, `stock_id`, `created_at`, `facial_data`) VALUES
(1, 'Administrador', '', NULL, 'admin', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', NULL, NULL, 1, 1, NULL, '2019-06-10 14:24:24', NULL),
(2, 'Victor Jugo', 'Cervantes', 'admin2', 'vicadmin@gmail.com', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', '', NULL, 1, 1, NULL, '2025-03-25 15:01:29', NULL),
(3, 'Ana', 'Fuentes', 'vend1', 'ana@vend.com', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', '', 10, 1, 3, 1, '2025-03-25 15:02:05', NULL),
(4, 'almacen', 'ista', 'almacenista', 'alm@email.com', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', '', NULL, 1, 2, 1, '2025-04-26 16:22:35', NULL),
(5, '450 SR', 'asa', 'admin', 'asas@sd.com', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', '', 1, 1, 3, 1, '2025-05-05 17:40:47', '[-0.06239792269128079,0.06424819559630404,0.04833609930661275,-0.029984097855782495,-0.04198371056996368,-0.03459040380730875,-0.07404561253210856,-0.07685115879505573,0.12200451049516055,-0.049329960488078764,0.17309758045198667,0.03138906923723349,-0.13487130988472634,-0.044861368670874875,0.00502374950227321,0.12457296037590707,-0.12816197753794453,-0.07617014638291475,-0.011072052506139303,-0.06898243888500766,0.02567053931147776,0.01256279738254689,0.010643062578524886,0.048463971723160346,-0.05240487617705224,-0.22999229247385353,-0.10731144884578833,-0.08407457263932314,0.01693782273227738,-0.02210428276041609,0.003807230848475198,0.03300116489399451,-0.06663782035394156,-0.03359059864280003,0.06346244373585709,0.049005398538979165,0.03440203722433755,-0.02080314786870447,0.14963456571653777,-0.006016336772439668,-0.09534058660703225,0.005558046285082815,0.03094071332287904,0.16519032265734246,0.11893185704043899,0.05530184192294463,0.04250815564720933,-0.03970086276962726,0.08500400134423991,-0.13973761047364885,0.04306722838958258,0.10342920267781924,0.07328713607547047,0.06685769154520749,0.018882113349968546,-0.11204849246088655,0.03439418182813828,0.0823879047151544,-0.13873115094459115,0.04936511875450717,0.041656641685776964,-0.11968534334790218,-0.006500043686342082,0.011705975769484777,0.20565982071368938,0.1119516508568839,-0.12678027926590227,-0.12063745432207328,0.05550721708667246,-0.09934741046958932,-0.08586504365656114,0.012076778841653821,-0.13589404451627188,-0.08084505346450416,-0.26191735673017763,0.03889538315281624,0.31211813258230175,0.06545426983167325,-0.1621523018616431,0.015235025933096917,-0.03238612807183291,-0.0010067566657861416,0.05608288329231405,0.11459677449830774,-0.05103217690307167,-0.04001086689174315,-0.06379600468301841,-0.02622197545265872,0.13717834935895867,0.0064958701169632056,-0.05195545562679364,0.14385603040179198,-0.035522637208277905,0.12124982315604704,-0.0013986197620082011,0.08616612056785013,-0.045764968952941916,-0.043449051582591804,-0.04186035388260438,-0.003051641565179929,0.0031417276782680506,0.01584694968621483,0.00899764159509559,0.08959348536182005,-0.11189435840113304,0.09523161733000358,-0.04346198452112139,0.015908187942933222,-0.04111358561184625,0.0405271625572452,-0.053983921927259544,-0.024501958300378927,0.10655518343598254,-0.15404464906465673,0.16918716122765512,0.14114088486729248,0.014352678261896942,0.09861775003324291,0.05240692242536289,0.051531035761355495,-0.0215087733145979,-0.027750670260603398,-0.0942287509081076,-0.041333290756116865,0.022026209460778278,-0.028287578226049805,0.061192878446671976,0.015425939714430414]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `xx`
--

CREATE TABLE `xx` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yy`
--

CREATE TABLE `yy` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `yy`
--

INSERT INTO `yy` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `box`
--
ALTER TABLE `box`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `buy_orders`
--
ALTER TABLE `buy_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `d`
--
ALTER TABLE `d`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `f`
--
ALTER TABLE `f`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `stock_destination_id` (`stock_destination_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `operation_type_id` (`operation_type_id`),
  ADD KEY `sell_id` (`sell_id`);

--
-- Indices de la tabla `operation_type`
--
ALTER TABLE `operation_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `p`
--
ALTER TABLE `p`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `sell_id` (`sell_id`),
  ADD KEY `payment_type_id` (`payment_type_id`);

--
-- Indices de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `saving`
--
ALTER TABLE `saving`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sell`
--
ALTER TABLE `sell`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `d_id` (`d_id`),
  ADD KEY `box_id` (`box_id`),
  ADD KEY `operation_type_id` (`operation_type_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indices de la tabla `spend`
--
ALTER TABLE `spend`
  ADD PRIMARY KEY (`id`),
  ADD KEY `box_id` (`box_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `xx`
--
ALTER TABLE `xx`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `yy`
--
ALTER TABLE `yy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `box`
--
ALTER TABLE `box`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `buy_orders`
--
ALTER TABLE `buy_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuration`
--
ALTER TABLE `configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `d`
--
ALTER TABLE `d`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `f`
--
ALTER TABLE `f`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `operation`
--
ALTER TABLE `operation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `operation_type`
--
ALTER TABLE `operation_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `p`
--
ALTER TABLE `p`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `price`
--
ALTER TABLE `price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `saving`
--
ALTER TABLE `saving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sell`
--
ALTER TABLE `sell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `spend`
--
ALTER TABLE `spend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `xx`
--
ALTER TABLE `xx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `yy`
--
ALTER TABLE `yy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `operation_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`),
  ADD CONSTRAINT `operation_ibfk_2` FOREIGN KEY (`stock_destination_id`) REFERENCES `stock` (`id`),
  ADD CONSTRAINT `operation_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `operation_ibfk_4` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_type` (`id`),
  ADD CONSTRAINT `operation_ibfk_5` FOREIGN KEY (`sell_id`) REFERENCES `sell` (`id`);

--
-- Filtros para la tabla `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`sell_id`) REFERENCES `sell` (`id`),
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_type` (`id`);

--
-- Filtros para la tabla `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `price_ibfk_2` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`);

--
-- Filtros para la tabla `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `sell`
--
ALTER TABLE `sell`
  ADD CONSTRAINT `sell_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `p` (`id`),
  ADD CONSTRAINT `sell_ibfk_2` FOREIGN KEY (`d_id`) REFERENCES `d` (`id`),
  ADD CONSTRAINT `sell_ibfk_3` FOREIGN KEY (`box_id`) REFERENCES `box` (`id`),
  ADD CONSTRAINT `sell_ibfk_4` FOREIGN KEY (`operation_type_id`) REFERENCES `operation_type` (`id`),
  ADD CONSTRAINT `sell_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `sell_ibfk_6` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`);

--
-- Filtros para la tabla `spend`
--
ALTER TABLE `spend`
  ADD CONSTRAINT `spend_ibfk_1` FOREIGN KEY (`box_id`) REFERENCES `box` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
