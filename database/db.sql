--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.1
-- Dumped by pg_dump version 9.6.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: course; Type: TABLE; Schema: public; Owner: teststudent
--

CREATE TABLE course (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    units smallint NOT NULL,
    prerequisites character varying,
    concurrents character varying,
    summer boolean
);


ALTER TABLE course OWNER TO teststudent;

--
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
-- Name: course_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: teststudent
--

ALTER SEQUENCE course_id_seq OWNED BY course.id;


--
-- Name: course id; Type: DEFAULT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course ALTER COLUMN id SET DEFAULT nextval('course_id_seq'::regclass);


--
-- Data for Name: course; Type: TABLE DATA; Schema: public; Owner: teststudent
--

COPY course (id, name, units, prerequisites, concurrents, summer) FROM stdin;
6	Out	17			\N
4	Test	3	Out	Me	t
5	Me	3			t
8	fasdf	3			f
\.


--
-- Name: course_id_seq; Type: SEQUENCE SET; Schema: public; Owner: teststudent
--

SELECT pg_catalog.setval('course_id_seq', 8, true);


--
-- Name: course course_pkey; Type: CONSTRAINT; Schema: public; Owner: teststudent
--

ALTER TABLE ONLY course
    ADD CONSTRAINT course_pkey PRIMARY KEY (id);


--
-- Name: course; Type: ACL; Schema: public; Owner: teststudent
--

GRANT SELECT,INSERT,DELETE,UPDATE ON TABLE course TO PUBLIC;


--
-- PostgreSQL database dump complete
--

