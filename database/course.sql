--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.1
-- Dumped by pg_dump version 9.6.1

-- Started on 2017-02-15 13:32:47 PST

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
-- TOC entry 2135 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 185 (class 1259 OID 16495)
-- Name: course; Type: TABLE; Schema: public; Owner: teststudent
--

CREATE TABLE course (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    units smallint NOT NULL,
    summer boolean,
    fall boolean,
    winter boolean,
    spring boolean,
    concurrents character varying(50),
    prerequisites character varying
);


ALTER TABLE course OWNER TO teststudent;

--
-- TOC entry 186 (class 1259 OID 16501)
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
-- TOC entry 2137 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: teststudent
--

ALTER SEQUENCE course_id_seq OWNED BY course.id;


--
-- TOC entry 2005 (class 2604 OID 16503)
-- Name: course id; Type: DEFAULT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course ALTER COLUMN id SET DEFAULT nextval('course_id_seq'::regclass);


--
-- TOC entry 2127 (class 0 OID 16495)
-- Dependencies: 185
-- Data for Name: course; Type: TABLE DATA; Schema: public; Owner: teststudent
--

COPY course (id, name, units, summer, fall, winter, spring, concurrents, prerequisites) FROM stdin;
10	My Course	3	f	f	f	f	\N	\N
11	My Other Course	3	f	f	f	f	\N	\N
\.


--
-- TOC entry 2138 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: teststudent
--

SELECT pg_catalog.setval('course_id_seq', 11, true);


--
-- TOC entry 2007 (class 2606 OID 16505)
-- Name: course course_pkey; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_pkey PRIMARY KEY (id);


--
-- TOC entry 2009 (class 2606 OID 16507)
-- Name: course coursename_unique; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course
    ADD CONSTRAINT coursename_unique UNIQUE (name);


--
-- TOC entry 2136 (class 0 OID 0)
-- Dependencies: 185
-- Name: course; Type: ACL; Schema: public; Owner: teststudent
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE course TO PUBLIC;


-- Completed on 2017-02-15 13:32:47 PST

--
-- PostgreSQL database dump complete
--

