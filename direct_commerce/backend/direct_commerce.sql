--
-- PostgreSQL database dump
--

\restrict f5iVn7jiDrdapBlhF8fvJVWGun0Ta2jAkNmB5LDlsbiXkqMXvkGwpjpsT9fQhb5

-- Dumped from database version 17.7
-- Dumped by pg_dump version 17.7

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    quantity integer DEFAULT 0,
    image character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: contact_messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.contact_messages (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    name character varying(100) NOT NULL,
    surname character varying(100) NOT NULL,
    email character varying(255) NOT NULL,
    phone_number character varying(20) NOT NULL,
    message text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.contact_messages OWNER TO postgres;

--
-- Name: newsletter_subscribers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.newsletter_subscribers (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    email character varying(255) NOT NULL,
    is_active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.newsletter_subscribers OWNER TO postgres;

--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    name character varying(255) NOT NULL,
    description_title character varying(255),
    description text,
    price numeric(10,2) NOT NULL,
    sold_price numeric(10,2),
    tag character varying(50),
    category_id uuid,
    main_image character varying(255),
    sub_images jsonb DEFAULT '[]'::jsonb,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: token_blacklist; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.token_blacklist (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    token text NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.token_blacklist OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    username character varying(100) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role_id uuid,
    two_factor_secret character varying(255),
    two_factor_enabled boolean DEFAULT false,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: whatsapp_inquiries; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.whatsapp_inquiries (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    name character varying(100) NOT NULL,
    surname character varying(100) NOT NULL,
    email character varying(255) NOT NULL,
    phone_number character varying(20) NOT NULL,
    country_code character varying(5) NOT NULL,
    country character varying(100) NOT NULL,
    town character varying(100) NOT NULL,
    address text NOT NULL,
    product_id uuid,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.whatsapp_inquiries OWNER TO postgres;

--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, description, quantity, image, created_at) FROM stdin;
0d7aa41d-2673-474e-ba1a-de0bcd0f5957	tets_category	tetsing category creation	0	/uploads/categories/image-1776680262890-823026096.jpg	2026-04-20 10:57:45.441117
9acbeea2-c7e8-4f73-b489-0f40a726583c	second_category	second category image preview	0	/uploads/categories/image-1776680300955-121495989.png	2026-04-20 11:18:20.976824
\.


--
-- Data for Name: contact_messages; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.contact_messages (id, name, surname, email, phone_number, message, created_at) FROM stdin;
b4d82935-7d99-44ed-aa67-43d1070dddd7	Jane	Doe	jane@example.com	987654321	I have a question about your products	2026-04-19 21:04:29.96991
23a097d9-a0e3-431d-89f4-e2c9dd8107d8	Sophie	Martin	sophie@example.com	699554433	Bonjour, je voudrais des informations sur vos produits	2026-04-20 11:35:19.124625
\.


--
-- Data for Name: newsletter_subscribers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.newsletter_subscribers (id, email, is_active, created_at) FROM stdin;
4bb5bfb0-be36-45ef-ad61-5de667654941	kingslydebruyne17@gmail.com	t	2026-04-20 15:19:41.857256
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products (id, name, description_title, description, price, sold_price, tag, category_id, main_image, sub_images, created_at) FROM stdin;
bc5f0461-121d-41f8-bd58-c237b0d41a3b	test_prod	test_prod	testing prod addition	30000.00	25000.00	new	0d7aa41d-2673-474e-ba1a-de0bcd0f5957	/uploads/products/main/main_image-1776700194325-165063972.png	["/uploads/products/sub/sub_images-1776700194341-156656805.png", "/uploads/products/sub/sub_images-1776700194354-64749250.PNG", "/uploads/products/sub/sub_images-1776700194361-158129819.jpg", "/uploads/products/sub/sub_images-1776700194375-222548485.png"]	2026-04-20 16:49:54.417953
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, name) FROM stdin;
e0943a92-7213-480d-8fd1-d3b37c1b2432	admin
62b076ca-11ec-4731-bced-4283d3b7f708	user
\.


--
-- Data for Name: token_blacklist; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.token_blacklist (id, token, created_at) FROM stdin;
7c698184-01ca-4464-96d7-e2a2b19a7a4d	eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjhmN2U4NGZhLTM1MTgtNDYwOC1hZTc4LThkZjVmYzYxNjdmZiIsInJvbGUiOiJlMDk0M2E5Mi03MjEzLTQ4MGQtOGZkMS1kM2IzN2MxYjI0MzIiLCJpYXQiOjE3NzY2Nzc1MTYsImV4cCI6MTc3NzI4MjMxNn0.QrMfU7mZ3cPwfSlWCD5UZuI19yk_urcLgWPv_IRobyc	2026-04-20 14:22:16.983113
63f0f912-cae4-482e-aef9-b3186b933b91	eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjhmN2U4NGZhLTM1MTgtNDYwOC1hZTc4LThkZjVmYzYxNjdmZiIsInJvbGUiOiJlMDk0M2E5Mi03MjEzLTQ4MGQtOGZkMS1kM2IzN2MxYjI0MzIiLCJpYXQiOjE3NzY2OTE3NzMsImV4cCI6MTc3NzI5NjU3M30.SURu80X5KcOrAxvb2ge0ORG1ejQNBvMqRMx8ABegHiw	2026-04-20 14:47:09.968157
dc67f8b8-84ba-4486-973d-733e5b7b395c	eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjhmN2U4NGZhLTM1MTgtNDYwOC1hZTc4LThkZjVmYzYxNjdmZiIsInJvbGUiOiJlMDk0M2E5Mi03MjEzLTQ4MGQtOGZkMS1kM2IzN2MxYjI0MzIiLCJpYXQiOjE3NzY2OTI4NTcsImV4cCI6MTc3NzI5NzY1N30.RBO5ojogJJ0giHIzjB-HpdyGbsBMn3cbZtNxqNtnx5c	2026-04-20 15:17:00.927687
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, email, password, role_id, two_factor_secret, two_factor_enabled, created_at) FROM stdin;
8f7e84fa-3518-4608-ae78-8df5fc6167ff	admin	kingslydebruyne17@gmail.com	$2b$10$LzaZ7XCscfhulAtc9Wv6U.YiRNH5IAqqLxdaTRtCTBLpo0g7OlZq6	e0943a92-7213-480d-8fd1-d3b37c1b2432	\N	f	2026-04-19 19:58:51.173983
\.


--
-- Data for Name: whatsapp_inquiries; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.whatsapp_inquiries (id, name, surname, email, phone_number, country_code, country, town, address, product_id, created_at) FROM stdin;
81a15916-9817-4135-9684-fcecb7961bee	John	Doe	john@example.com	123456789	+237	Cameroon	Douala	123 Main Street	\N	2026-04-19 20:59:33.910697
24eb88e2-a3d6-4ca2-a319-89aebab04c35	Marie	Claire	marie@example.com	677889900	+237	Cameroun	Yaoundé	Avenue Centrale	\N	2026-04-20 11:29:24.494231
1c068b1f-97f2-4796-b8b7-7c4a5c790f4e	kinsly	laurince	kingslydebruyne17@gmail.com	690981048	+237	Cameroun	dschang	dschng rondo	bc5f0461-121d-41f8-bd58-c237b0d41a3b	2026-04-20 17:40:18.87151
\.


--
-- Name: categories categories_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_name_key UNIQUE (name);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: contact_messages contact_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.contact_messages
    ADD CONSTRAINT contact_messages_pkey PRIMARY KEY (id);


--
-- Name: newsletter_subscribers newsletter_subscribers_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newsletter_subscribers
    ADD CONSTRAINT newsletter_subscribers_email_key UNIQUE (email);


--
-- Name: newsletter_subscribers newsletter_subscribers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newsletter_subscribers
    ADD CONSTRAINT newsletter_subscribers_pkey PRIMARY KEY (id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: roles roles_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_key UNIQUE (name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: token_blacklist token_blacklist_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.token_blacklist
    ADD CONSTRAINT token_blacklist_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: whatsapp_inquiries whatsapp_inquiries_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.whatsapp_inquiries
    ADD CONSTRAINT whatsapp_inquiries_pkey PRIMARY KEY (id);


--
-- Name: products products_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_category_id_fkey FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE SET NULL;


--
-- Name: users users_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(id);


--
-- Name: whatsapp_inquiries whatsapp_inquiries_product_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.whatsapp_inquiries
    ADD CONSTRAINT whatsapp_inquiries_product_id_fkey FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict f5iVn7jiDrdapBlhF8fvJVWGun0Ta2jAkNmB5LDlsbiXkqMXvkGwpjpsT9fQhb5

