--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.1
-- Dumped by pg_dump version 9.6.1

-- Started on 2017-03-08 13:55:01 PST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 12392)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 185 (class 1259 OID 16576)
-- Name: course; Type: TABLE; Schema: public; Owner: teststudent
--

CREATE TABLE course (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    units real NOT NULL,
    summer boolean,
    fall boolean,
    winter boolean,
    spring boolean
);


ALTER TABLE course OWNER TO teststudent;

--
-- TOC entry 186 (class 1259 OID 16579)
-- Name: course_concurrents; Type: TABLE; Schema: public; Owner: teststudent
--

CREATE TABLE course_concurrents (
    id integer NOT NULL,
    from_id integer,
    to_id integer
);


ALTER TABLE course_concurrents OWNER TO teststudent;

--
-- TOC entry 187 (class 1259 OID 16582)
-- Name: course_concurrents_id_seq; Type: SEQUENCE; Schema: public; Owner: teststudent
--

CREATE SEQUENCE course_concurrents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_concurrents_id_seq OWNER TO teststudent;

--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 187
-- Name: course_concurrents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: teststudent
--

ALTER SEQUENCE course_concurrents_id_seq OWNED BY course_concurrents.id;


--
-- TOC entry 188 (class 1259 OID 16584)
-- Name: course_id_seq; Type: SEQUENCE; Schema: public; Owner: teststudent
--

CREATE SEQUENCE course_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_id_seq OWNER TO teststudent;

--
-- TOC entry 2160 (class 0 OID 0)
-- Dependencies: 188
-- Name: course_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: teststudent
--

ALTER SEQUENCE course_id_seq OWNED BY course.id;


--
-- TOC entry 189 (class 1259 OID 16586)
-- Name: course_prerequisites; Type: TABLE; Schema: public; Owner: teststudent
--

CREATE TABLE course_prerequisites (
    from_id integer,
    to_id integer,
    id integer NOT NULL
);


ALTER TABLE course_prerequisites OWNER TO teststudent;

--
-- TOC entry 190 (class 1259 OID 16589)
-- Name: course_prerequisites_id_seq; Type: SEQUENCE; Schema: public; Owner: teststudent
--

CREATE SEQUENCE course_prerequisites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE course_prerequisites_id_seq OWNER TO teststudent;

--
-- TOC entry 2161 (class 0 OID 0)
-- Dependencies: 190
-- Name: course_prerequisites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: teststudent
--

ALTER SEQUENCE course_prerequisites_id_seq OWNED BY course_prerequisites.id;


--
-- TOC entry 2016 (class 2604 OID 16591)
-- Name: course id; Type: DEFAULT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course ALTER COLUMN id SET DEFAULT nextval('course_id_seq'::regclass);


--
-- TOC entry 2017 (class 2604 OID 16592)
-- Name: course_concurrents id; Type: DEFAULT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course_concurrents ALTER COLUMN id SET DEFAULT nextval('course_concurrents_id_seq'::regclass);


--
-- TOC entry 2018 (class 2604 OID 16593)
-- Name: course_prerequisites id; Type: DEFAULT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course_prerequisites ALTER COLUMN id SET DEFAULT nextval('course_prerequisites_id_seq'::regclass);


--
-- TOC entry 2144 (class 0 OID 16576)
-- Dependencies: 185
-- Data for Name: course; Type: TABLE DATA; Schema: public; Owner: teststudent
--

COPY course (id, name, units, summer, fall, winter, spring) FROM stdin;
11	My Other Course	3	f	f	f	f
12	My New Course	3	f	f	f	f
16	CMPS123	5	t	t	f	t
17	My Test Course	1	f	f	f	f
10	My Course	2.5	f	f	f	f
\.


--
-- TOC entry 2145 (class 0 OID 16579)
-- Dependencies: 186
-- Data for Name: course_concurrents; Type: TABLE DATA; Schema: public; Owner: teststudent
--

COPY course_concurrents (id, from_id, to_id) FROM stdin;
14	16	12
\.


--
-- TOC entry 2162 (class 0 OID 0)
-- Dependencies: 187
-- Name: course_concurrents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: teststudent
--

SELECT pg_catalog.setval('course_concurrents_id_seq', 14, true);


--
-- TOC entry 2163 (class 0 OID 0)
-- Dependencies: 188
-- Name: course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: teststudent
--

SELECT pg_catalog.setval('course_id_seq', 17, true);


--
-- TOC entry 2148 (class 0 OID 16586)
-- Dependencies: 189
-- Data for Name: course_prerequisites; Type: TABLE DATA; Schema: public; Owner: teststudent
--

COPY course_prerequisites (from_id, to_id, id) FROM stdin;
\.


--
-- TOC entry 2164 (class 0 OID 0)
-- Dependencies: 190
-- Name: course_prerequisites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: teststudent
--

SELECT pg_catalog.setval('course_prerequisites_id_seq', 1, true);


--
-- TOC entry 2024 (class 2606 OID 16595)
-- Name: course_concurrents course_concurrents_pkey; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course_concurrents
    ADD CONSTRAINT course_concurrents_pkey PRIMARY KEY (id);


--
-- TOC entry 2020 (class 2606 OID 16597)
-- Name: course course_pkey; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_pkey PRIMARY KEY (id);


--
-- TOC entry 2026 (class 2606 OID 16599)
-- Name: course_prerequisites course_prerequisites_pkey; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course_prerequisites
    ADD CONSTRAINT course_prerequisites_pkey PRIMARY KEY (id);


--
-- TOC entry 2022 (class 2606 OID 16601)
-- Name: course coursename_unique; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course
    ADD CONSTRAINT coursename_unique UNIQUE (name);


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM postgres;


--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 185
-- Name: course; Type: ACL; Schema: public; Owner: teststudent
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE course TO PUBLIC;


-- Completed on 2017-03-08 13:55:02 PST

--
-- PostgreSQL database dump complete
--

