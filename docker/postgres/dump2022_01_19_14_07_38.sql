--
-- PostgreSQL database dump
--

-- Dumped from database version 13.5 (Debian 13.5-1.pgdg110+1)
-- Dumped by pg_dump version 13.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE IF EXISTS quicknotes_db;
--
-- Name: quicknotes_db; Type: DATABASE; Schema: -; Owner: admin
--

CREATE DATABASE quicknotes_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'en_US.utf8';


ALTER DATABASE quicknotes_db OWNER TO admin;

\connect quicknotes_db

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: quicknotes_schema; Type: SCHEMA; Schema: -; Owner: admin
--

CREATE SCHEMA quicknotes_schema;


ALTER SCHEMA quicknotes_schema OWNER TO admin;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: note; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema.note (
    note_id uuid NOT NULL,
    title character varying(150),
    text text NOT NULL,
    creation_datetime timestamp without time zone DEFAULT now() NOT NULL,
    user_id uuid,
    last_edit timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE quicknotes_schema.note OWNER TO admin;

--
-- Name: note_public_share; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema.note_public_share (
    share_id uuid NOT NULL,
    note_id uuid NOT NULL
);


ALTER TABLE quicknotes_schema.note_public_share OWNER TO admin;

--
-- Name: note_share; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema.note_share (
    note_id uuid NOT NULL,
    user_id uuid NOT NULL
);


ALTER TABLE quicknotes_schema.note_share OWNER TO admin;

--
-- Name: note_tag; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema.note_tag (
    note_id uuid NOT NULL,
    tag_id uuid NOT NULL
);


ALTER TABLE quicknotes_schema.note_tag OWNER TO admin;

--
-- Name: session; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema.session (
    session_id uuid NOT NULL,
    user_id uuid NOT NULL,
    expiration timestamp without time zone NOT NULL,
    last_active timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE quicknotes_schema.session OWNER TO admin;

--
-- Name: tag; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema.tag (
    tag_id uuid NOT NULL,
    tag_name character varying(200) NOT NULL,
    user_id uuid NOT NULL
);


ALTER TABLE quicknotes_schema.tag OWNER TO admin;

--
-- Name: user; Type: TABLE; Schema: quicknotes_schema; Owner: admin
--

CREATE TABLE quicknotes_schema."user" (
    user_id uuid NOT NULL,
    username character varying(100) NOT NULL,
    password_hash character varying(60) NOT NULL,
    email character varying(200) NOT NULL
);


ALTER TABLE quicknotes_schema."user" OWNER TO admin;

--
-- Data for Name: note; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--

INSERT INTO quicknotes_schema.note VALUES ('a59c244a-7924-11ec-90d6-0242ac120003', 'note 1 of user 1', 'this is text', '2022-01-19 13:38:27', 'd243c592-7920-11ec-90d6-0242ac120003', '2022-01-19 13:38:54');
INSERT INTO quicknotes_schema.note VALUES ('d4561796-7924-11ec-90d6-0242ac120003', 'note 2 of user 1', 'this is also text', '2022-01-19 13:39:42', 'd243c592-7920-11ec-90d6-0242ac120003', '2022-01-19 13:39:56');
INSERT INTO quicknotes_schema.note VALUES ('6ab27a42-7924-11ec-90d6-0242ac120003', 'note 1 fo user 2', 'this is text of note 1 of user 2', '2022-01-19 13:41:14', '6ab17a42-7924-11ec-90d6-0242ac120003', '2022-01-19 13:41:38');
INSERT INTO quicknotes_schema.note VALUES ('6ac27a42-7954-11ec-90d6-0242ac120003', 'note 2 of user 2', 'this is text of note 2 of user 2', '2022-01-19 13:42:53', '6ab17a42-7924-11ec-90d6-0242ac120003', '2022-01-19 13:43:14');


--
-- Data for Name: note_public_share; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--

INSERT INTO quicknotes_schema.note_public_share VALUES ('03ff9028-7928-11ec-90d6-0242ac120003', 'd4561796-7924-11ec-90d6-0242ac120003');


--
-- Data for Name: note_share; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--

INSERT INTO quicknotes_schema.note_share VALUES ('a59c244a-7924-11ec-90d6-0242ac120003', '6ab17a42-7924-11ec-90d6-0242ac120003');
INSERT INTO quicknotes_schema.note_share VALUES ('6ab27a42-7924-11ec-90d6-0242ac120003', 'd243c592-7920-11ec-90d6-0242ac120003');


--
-- Data for Name: note_tag; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--

INSERT INTO quicknotes_schema.note_tag VALUES ('d4561796-7924-11ec-90d6-0242ac120003', 'c87fd73a-7925-11ec-90d6-0242ac120003');
INSERT INTO quicknotes_schema.note_tag VALUES ('6ab27a42-7924-11ec-90d6-0242ac120003', '03b8fdae-7926-11ec-90d6-0242ac120003');


--
-- Data for Name: session; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--



--
-- Data for Name: tag; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--

INSERT INTO quicknotes_schema.tag VALUES ('c87fd73a-7925-11ec-90d6-0242ac120003', 'tag 1 for user 1', 'd243c592-7920-11ec-90d6-0242ac120003');
INSERT INTO quicknotes_schema.tag VALUES ('f663b220-7925-11ec-90d6-0242ac120003', 'tag 2 for user 1', 'd243c592-7920-11ec-90d6-0242ac120003');
INSERT INTO quicknotes_schema.tag VALUES ('03b8fdae-7926-11ec-90d6-0242ac120003', 'tag 1 for user 2', '6ab17a42-7924-11ec-90d6-0242ac120003');
INSERT INTO quicknotes_schema.tag VALUES ('03b8fddd-7926-11ec-90d6-0242ac120003', 'tag 2 for user 2', '6ab17a42-7924-11ec-90d6-0242ac120003');


--
-- Data for Name: user; Type: TABLE DATA; Schema: quicknotes_schema; Owner: admin
--

INSERT INTO quicknotes_schema."user" VALUES ('d243c592-7920-11ec-90d6-0242ac120003', 'user 1', '$2y$10$y7XQELHmtgpikLPH8eA34OFm7eqOA2XcEeruZKkL2J0ZAo4ABkaLW', 'email1@domain.com');
INSERT INTO quicknotes_schema."user" VALUES ('6ab17a42-7924-11ec-90d6-0242ac120003', 'user 2', '$2y$10$wjh8WyMyG1bj6jQrq2I0GeJL7DoVO8CQKw33uFnidNlNtJxLD3gAG', 'email2@domain.com');


--
-- Name: note note_pk; Type: CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note
    ADD CONSTRAINT note_pk PRIMARY KEY (note_id);


--
-- Name: note_public_share note_public_share_pk; Type: CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note_public_share
    ADD CONSTRAINT note_public_share_pk PRIMARY KEY (share_id, note_id);


--
-- Name: session session_pk; Type: CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.session
    ADD CONSTRAINT session_pk PRIMARY KEY (session_id, user_id);


--
-- Name: tag tag_pk; Type: CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.tag
    ADD CONSTRAINT tag_pk PRIMARY KEY (tag_id);


--
-- Name: user user_pk; Type: CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema."user"
    ADD CONSTRAINT user_pk PRIMARY KEY (user_id);


--
-- Name: note_note_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX note_note_id_uindex ON quicknotes_schema.note USING btree (note_id);


--
-- Name: note_public_share_note_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX note_public_share_note_id_uindex ON quicknotes_schema.note_public_share USING btree (note_id);


--
-- Name: note_public_share_share_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX note_public_share_share_id_uindex ON quicknotes_schema.note_public_share USING btree (share_id);


--
-- Name: session_session_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX session_session_id_uindex ON quicknotes_schema.session USING btree (session_id);


--
-- Name: session_user_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX session_user_id_uindex ON quicknotes_schema.session USING btree (user_id);


--
-- Name: tag_tag_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX tag_tag_id_uindex ON quicknotes_schema.tag USING btree (tag_id);


--
-- Name: user_email_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX user_email_uindex ON quicknotes_schema."user" USING btree (email);


--
-- Name: user_user_id_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX user_user_id_uindex ON quicknotes_schema."user" USING btree (user_id);


--
-- Name: user_username_uindex; Type: INDEX; Schema: quicknotes_schema; Owner: admin
--

CREATE UNIQUE INDEX user_username_uindex ON quicknotes_schema."user" USING btree (username);


--
-- Name: note_public_share note_public_share_note_note_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note_public_share
    ADD CONSTRAINT note_public_share_note_note_id_fk FOREIGN KEY (note_id) REFERENCES quicknotes_schema.note(note_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: note_share note_share_note_note_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note_share
    ADD CONSTRAINT note_share_note_note_id_fk FOREIGN KEY (note_id) REFERENCES quicknotes_schema.note(note_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: note_share note_share_user_user_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note_share
    ADD CONSTRAINT note_share_user_user_id_fk FOREIGN KEY (user_id) REFERENCES quicknotes_schema."user"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: note_tag note_tag_note_note_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note_tag
    ADD CONSTRAINT note_tag_note_note_id_fk FOREIGN KEY (note_id) REFERENCES quicknotes_schema.note(note_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: note_tag note_tag_tag_tag_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note_tag
    ADD CONSTRAINT note_tag_tag_tag_id_fk FOREIGN KEY (tag_id) REFERENCES quicknotes_schema.tag(tag_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: note note_user_user_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.note
    ADD CONSTRAINT note_user_user_id_fk FOREIGN KEY (user_id) REFERENCES quicknotes_schema."user"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: session session_user_user_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.session
    ADD CONSTRAINT session_user_user_id_fk FOREIGN KEY (user_id) REFERENCES quicknotes_schema."user"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tag tag_user_user_id_fk; Type: FK CONSTRAINT; Schema: quicknotes_schema; Owner: admin
--

ALTER TABLE ONLY quicknotes_schema.tag
    ADD CONSTRAINT tag_user_user_id_fk FOREIGN KEY (user_id) REFERENCES quicknotes_schema."user"(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

