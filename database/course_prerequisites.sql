--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.1
-- Dumped by pg_dump version 9.6.1

-- Started on 2017-02-12 22:50:08 PST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 187 (class 1259 OID 16508)
-- Name: course_prerequisites; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE course_prerequisites (
    id integer NOT NULL,
    course_id integer,
    prerequisite_id integer
);


ALTER TABLE course_prerequisites OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 16511)
-- Name: course_prerequisites_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE course_prerequisites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_prerequisites_id_seq OWNER TO postgres;

--
-- TOC entry 2138 (class 0 OID 0)
-- Dependencies: 188
-- Name: course_prerequisites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE course_prerequisites_id_seq OWNED BY course_prerequisites.id;


--
-- TOC entry 2014 (class 2604 OID 16513)
-- Name: course_prerequisites id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY course_prerequisites ALTER COLUMN id SET DEFAULT nextval('course_prerequisites_id_seq'::regclass);


--
-- TOC entry 2132 (class 0 OID 16508)
-- Dependencies: 187
-- Data for Name: course_prerequisites; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY course_prerequisites (id, course_id, prerequisite_id) FROM stdin;
\.


--
-- TOC entry 2139 (class 0 OID 0)
-- Dependencies: 188
-- Name: course_prerequisites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('course_prerequisites_id_seq', 1, false);


-- Completed on 2017-02-12 22:50:09 PST

--
-- PostgreSQL database dump complete
--

