USE [SPPI]
GO
/****** Object:  Table [dbo].[brod]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[brod](
	[id_brod] [int] IDENTITY(1,1) NOT NULL,
	[naziv] [varchar](100) NOT NULL,
	[tip] [varchar](50) NULL,
	[tonaza] [int] NOT NULL,
	[vlasnik] [varchar](100) NULL,
	[luka] [varchar](25) NULL,
	[posada] [varchar](50) NULL,
	[ruta] [varchar](100) NULL,
	[teret] [varchar](200) NULL,
 CONSTRAINT [PK_brod] PRIMARY KEY CLUSTERED 
(
	[id_brod] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[datum]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[datum](
	[id_datum] [int] IDENTITY(1,1) NOT NULL,
	[datum] [date] NOT NULL,
	[dan_broj] [tinyint] NOT NULL,
	[dan_naziv] [varchar](10) NOT NULL,
	[mjesec_broj] [tinyint] NOT NULL,
	[mjesec_naziv] [varchar](10) NOT NULL,
	[godina] [smallint] NOT NULL,
 CONSTRAINT [PK_datum] PRIMARY KEY CLUSTERED 
(
	[id_datum] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_datum] UNIQUE NONCLUSTERED 
(
	[datum] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[drzava]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[drzava](
	[id_drzava] [int] IDENTITY(1,1) NOT NULL,
	[naziv] [varchar](100) NOT NULL,
	[kontinent] [varchar](25) NOT NULL,
	[kratica] [char](2) NOT NULL,
 CONSTRAINT [PK_drzava] PRIMARY KEY CLUSTERED 
(
	[id_drzava] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[konvoj]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[konvoj](
	[id_konvoj] [int] IDENTITY(1,1) NOT NULL,
	[naziv] [varchar](10) NOT NULL,
 CONSTRAINT [PK_konvoj] PRIMARY KEY CLUSTERED 
(
	[id_konvoj] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_konvoj] UNIQUE NONCLUSTERED 
(
	[naziv] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[podmornica]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[podmornica](
	[id_podmornica] [int] IDENTITY(1,1) NOT NULL,
	[naziv] [varchar](10) NOT NULL,
	[narucena] [date] NOT NULL,
	[brodogradiliste] [varchar](50) NOT NULL,
	[grad] [varchar](20) NOT NULL,
	[u_sluzbi] [date] NOT NULL,
 CONSTRAINT [PK_podmornica] PRIMARY KEY CLUSTERED 
(
	[id_podmornica] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_podmornica] UNIQUE NONCLUSTERED 
(
	[naziv] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[potopljeno]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[potopljeno](
	[id_datum] [int] NOT NULL,
	[id_podmornica] [int] NOT NULL,
	[id_tip] [int] NOT NULL,
	[id_zapovjednik] [int] NOT NULL,
	[id_brod] [int] NOT NULL,
	[id_drzava] [int] NOT NULL,
	[id_konvoj] [int] NULL,
 CONSTRAINT [PK_potopljeno] PRIMARY KEY CLUSTERED 
(
	[id_datum] ASC,
	[id_podmornica] ASC,
	[id_tip] ASC,
	[id_zapovjednik] ASC,
	[id_brod] ASC,
	[id_drzava] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_potopljeno] UNIQUE NONCLUSTERED 
(
	[id_brod] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tip]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tip](
	[id_tip] [int] IDENTITY(1,1) NOT NULL,
	[naziv] [varchar](10) NOT NULL,
	[istisnina_povrsina] [smallint] NOT NULL,
	[ististina_zaronjena] [smallint] NOT NULL,
	[ististina_ukupno] [smallint] NOT NULL,
	[duzina] [decimal](4, 2) NOT NULL,
	[sirina] [decimal](4, 2) NOT NULL,
	[gaz] [decimal](4, 2) NOT NULL,
	[visina] [decimal](4, 2) NOT NULL,
	[ks_povrsina] [smallint] NOT NULL,
	[ks_zaronjena] [smallint] NOT NULL,
	[brzina_povrsina] [decimal](4, 2) NOT NULL,
	[brzina_zaronjena] [decimal](4, 2) NOT NULL,
	[domet_povrsina] [smallint] NOT NULL,
	[domet_zaronjena] [smallint] NOT NULL,
	[torpeda] [tinyint] NOT NULL,
	[mine] [tinyint] NOT NULL,
	[naoruzanje] [varchar](50) NOT NULL,
	[posada] [nchar](10) NOT NULL,
	[dubina] [smallint] NOT NULL,
 CONSTRAINT [PK_tip] PRIMARY KEY CLUSTERED 
(
	[id_tip] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_tip] UNIQUE NONCLUSTERED 
(
	[naziv] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[zapovjednik]    Script Date: 09/06/2018 10:11:23 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[zapovjednik](
	[id_zapovjednik] [int] IDENTITY(1,1) NOT NULL,
	[naziv] [varchar](100) NOT NULL,
	[rank] [varchar](50) NOT NULL,
	[roden] [date] NOT NULL,
	[mjesto] [varchar](50) NULL,
 CONSTRAINT [PK_zapovjednik] PRIMARY KEY CLUSTERED 
(
	[id_zapovjednik] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [UQ_drzava]    Script Date: 09/06/2018 10:11:23 ******/
CREATE NONCLUSTERED INDEX [UQ_drzava] ON [dbo].[drzava]
(
	[naziv] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_brod] FOREIGN KEY([id_brod])
REFERENCES [dbo].[brod] ([id_brod])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_brod]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_datum] FOREIGN KEY([id_datum])
REFERENCES [dbo].[datum] ([id_datum])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_datum]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_drzava] FOREIGN KEY([id_drzava])
REFERENCES [dbo].[drzava] ([id_drzava])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_drzava]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_konvoj] FOREIGN KEY([id_konvoj])
REFERENCES [dbo].[konvoj] ([id_konvoj])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_konvoj]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_podmornica] FOREIGN KEY([id_podmornica])
REFERENCES [dbo].[podmornica] ([id_podmornica])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_podmornica]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_tip] FOREIGN KEY([id_tip])
REFERENCES [dbo].[tip] ([id_tip])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_tip]
GO
ALTER TABLE [dbo].[potopljeno]  WITH CHECK ADD  CONSTRAINT [FK_potopljeno_zapovjednik] FOREIGN KEY([id_zapovjednik])
REFERENCES [dbo].[zapovjednik] ([id_zapovjednik])
GO
ALTER TABLE [dbo].[potopljeno] CHECK CONSTRAINT [FK_potopljeno_zapovjednik]
GO
