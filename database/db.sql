--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.1
-- Dumped by pg_dump version 9.6.2

-- Started on 2017-03-06 17:29:43

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 13277)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 3043 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 185 (class 1259 OID 2444088)
-- Name: course; Type: TABLE; Schema: public; Owner: dxucgijykcoqlp
--

CREATE TABLE course (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    units smallint NOT NULL,
    summer boolean,
    fall boolean,
    winter boolean,
    spring boolean
);


ALTER TABLE course OWNER TO dxucgijykcoqlp;

--
-- TOC entry 190 (class 1259 OID 2444112)
-- Name: course_concurrents; Type: TABLE; Schema: public; Owner: dxucgijykcoqlp
--

CREATE TABLE course_concurrents (
    id integer NOT NULL,
    from_id integer,
    to_id integer
);


ALTER TABLE course_concurrents OWNER TO dxucgijykcoqlp;

--
-- TOC entry 189 (class 1259 OID 2444110)
-- Name: course_concurrents_id_seq; Type: SEQUENCE; Schema: public; Owner: dxucgijykcoqlp
--

CREATE SEQUENCE course_concurrents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_concurrents_id_seq OWNER TO dxucgijykcoqlp;

--
-- TOC entry 3046 (class 0 OID 0)
-- Dependencies: 189
-- Name: course_concurrents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dxucgijykcoqlp
--

ALTER SEQUENCE course_concurrents_id_seq OWNED BY course_concurrents.id;


--
-- TOC entry 186 (class 1259 OID 2444094)
-- Name: course_id_seq; Type: SEQUENCE; Schema: public; Owner: dxucgijykcoqlp
--

CREATE SEQUENCE course_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_id_seq OWNER TO dxucgijykcoqlp;

--
-- TOC entry 3047 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dxucgijykcoqlp
--

ALTER SEQUENCE course_id_seq OWNED BY course.id;


--
-- TOC entry 188 (class 1259 OID 2444103)
-- Name: course_prerequisites; Type: TABLE; Schema: public; Owner: dxucgijykcoqlp
--

CREATE TABLE course_prerequisites (
    from_id integer,
    to_id integer,
    id integer NOT NULL
);


ALTER TABLE course_prerequisites OWNER TO dxucgijykcoqlp;

--
-- TOC entry 187 (class 1259 OID 2444101)
-- Name: course_prerequisites_id_seq; Type: SEQUENCE; Schema: public; Owner: dxucgijykcoqlp
--

CREATE SEQUENCE course_prerequisites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_prerequisites_id_seq OWNER TO dxucgijykcoqlp;

--
-- TOC entry 3048 (class 0 OID 0)
-- Dependencies: 187
-- Name: course_prerequisites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dxucgijykcoqlp
--

ALTER SEQUENCE course_prerequisites_id_seq OWNED BY course_prerequisites.id;


--
-- TOC entry 2902 (class 2604 OID 2444096)
-- Name: course id; Type: DEFAULT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course ALTER COLUMN id SET DEFAULT nextval('course_id_seq'::regclass);


--
-- TOC entry 2904 (class 2604 OID 2444115)
-- Name: course_concurrents id; Type: DEFAULT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course_concurrents ALTER COLUMN id SET DEFAULT nextval('course_concurrents_id_seq'::regclass);


--
-- TOC entry 2903 (class 2604 OID 2444106)
-- Name: course_prerequisites id; Type: DEFAULT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course_prerequisites ALTER COLUMN id SET DEFAULT nextval('course_prerequisites_id_seq'::regclass);


--
-- TOC entry 3030 (class 0 OID 2444088)
-- Dependencies: 185
-- Data for Name: course; Type: TABLE DATA; Schema: public; Owner: dxucgijykcoqlp
--

COPY course (id, name, units, summer, fall, winter, spring) FROM stdin;
10	My Course	3	f	f	f	f
11	My Other Course	3	f	f	f	f
\.


--
-- TOC entry 3035 (class 0 OID 2444112)
-- Dependencies: 190
-- Data for Name: course_concurrents; Type: TABLE DATA; Schema: public; Owner: dxucgijykcoqlp
--

COPY course_concurrents (id, from_id, to_id) FROM stdin;
\.


--
-- TOC entry 3049 (class 0 OID 0)
-- Dependencies: 189
-- Name: course_concurrents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dxucgijykcoqlp
--

SELECT pg_catalog.setval('course_concurrents_id_seq', 1, false);


--
-- TOC entry 3050 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dxucgijykcoqlp
--

SELECT pg_catalog.setval('course_id_seq', 11, true);


--
-- TOC entry 3033 (class 0 OID 2444103)
-- Dependencies: 188
-- Data for Name: course_prerequisites; Type: TABLE DATA; Schema: public; Owner: dxucgijykcoqlp
--

COPY course_prerequisites (from_id, to_id, id) FROM stdin;
\.


--
-- TOC entry 3051 (class 0 OID 0)
-- Dependencies: 187
-- Name: course_prerequisites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dxucgijykcoqlp
--

SELECT pg_catalog.setval('course_prerequisites_id_seq', 1, false);


--
-- TOC entry 2912 (class 2606 OID 2444117)
-- Name: course_concurrents course_concurrents_pkey; Type: CONSTRAINT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course_concurrents
    ADD CONSTRAINT course_concurrents_pkey PRIMARY KEY (id);


--
-- TOC entry 2906 (class 2606 OID 2444098)
-- Name: course course_pkey; Type: CONSTRAINT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_pkey PRIMARY KEY (id);


--
-- TOC entry 2910 (class 2606 OID 2444109)
-- Name: course_prerequisites course_prerequisites_pkey; Type: CONSTRAINT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course_prerequisites
    ADD CONSTRAINT course_prerequisites_pkey PRIMARY KEY (id);


--
-- TOC entry 2908 (class 2606 OID 2444100)
-- Name: course coursename_unique; Type: CONSTRAINT; Schema: public; Owner: dxucgijykcoqlp
--

ALTER TABLE ONLY course
    ADD CONSTRAINT coursename_unique UNIQUE (name);


--
-- TOC entry 3042 (class 0 OID 0)
-- Dependencies: 7
-- Name: public; Type: ACL; Schema: -; Owner: dxucgijykcoqlp
--

REVOKE ALL ON SCHEMA public FROM postgres;
REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO dxucgijykcoqlp;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 3044 (class 0 OID 0)
-- Dependencies: 575
-- Name: plpgsql; Type: ACL; Schema: -; Owner: postgres
--

GRANT ALL ON LANGUAGE plpgsql TO dxucgijykcoqlp;


--
-- TOC entry 3045 (class 0 OID 0)
-- Dependencies: 185
-- Name: course; Type: ACL; Schema: public; Owner: dxucgijykcoqlp
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE course TO PUBLIC;


-- Completed on 2017-03-06 17:29:55

--
-- PostgreSQL database dump complete
--

